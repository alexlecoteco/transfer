<?php

namespace HyperfTest\Cases;

use App\ExternalServices\TransactionNotificator\TransactionNotificator;
use App\ExternalServices\TransactionValidator\TransactionValidator;
use App\Repositories\Wallets\WalletsEloquentRepository;
use App\Resources\Transfer\ExecuteTransferResource;
use App\Stubs\TransactionNotificator\TransactionNotificatorServiceStub;
use App\Stubs\TransactionValidator\TransactionValidatorServiceStub;
use Hyperf\Cache\Cache;
use HyperfTest\AbstractTest;
use function Hyperf\Support\make;

class TransferTest extends AbstractTest
{
    private const TRANSFER_URI = '/transfer';

    public function makeTransferRequest(int $payer, int $payee, int $amount): array
    {
        return [
            'payer' => $payer,
            'payee' => $payee,
            'amount' => $amount
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->freshStart();
        $this->createTestingModels();
    }

    public function testRequestRestrictions(): void
    {
        $firstUser = 1;
        $secondUser = 2;
        $lojistUser = 3;
        $amount = 500;

        $walletsEloquentRepository = WalletsEloquentRepository::instantiate();

        $response = $this->post(self::TRANSFER_URI, []);

        $this->expectFieldRuleTest($response, 'payer', 'required', null);
        $this->expectFieldRuleTest($response, 'payee', 'required', null);
        $this->expectFieldRuleTest($response, 'amount', 'required', null);

        $secondResponse = $this->post(self::TRANSFER_URI, $this->makeTransferRequest($firstUser,$secondUser,$amount));
        $thirdResponse = $this->post(self::TRANSFER_URI, $this->makeTransferRequest($secondUser,$secondUser,$amount * 2));
        $fourthResponse = $this->post(self::TRANSFER_URI, $this->makeTransferRequest($lojistUser, $firstUser, $amount));

        $firstUserWallet = $walletsEloquentRepository->findWalletByUserId($firstUser);
        $secondUserWallet = $walletsEloquentRepository->findWalletByUserId($secondUser);
        $lojistUserWallet = $walletsEloquentRepository->findWalletByUserId($lojistUser);

        $this->assertEquals(self::FIRST_USER_BALANCE, $firstUserWallet->balance);
        $this->assertEquals(self::SECOND_USER_BALANCE, $secondUserWallet->balance);
        $this->assertEquals(self::LOJIST_BALANCE, $lojistUserWallet->balance);

        $this->assertEquals(null, $secondResponse);
        $this->assertEquals(null, $thirdResponse);
        $this->assertEquals(null, $fourthResponse);
    }

    public function testRequestSuccess(): void
    {
        $amount = 50;
        $firstUserId = 1;
        $secondUserId = 2;
        $lojistUserId = 3;

        $walletsEloquentRepository = WalletsEloquentRepository::instantiate();

        $response = $this->post(self::TRANSFER_URI,
            $this->makeTransferRequest($firstUserId,$secondUserId,$amount)
        );

        $firstUserWaller = $walletsEloquentRepository->findWalletByUserId($firstUserId);
        $secondUserWallet = $walletsEloquentRepository->findWalletByUserId($secondUserId);

        $this->assertEquals($firstUserWaller->balance, self::FIRST_USER_BALANCE - $amount);
        $this->assertEquals($secondUserWallet->balance, self::SECOND_USER_BALANCE + $amount);
        $this->assertJson(
            json_encode($response),
            ExecuteTransferResource::make(['payerWallet' => $firstUserWaller, 'payeeWallet' => $secondUserWallet])
        );

        $secondResponse = $this->post(self::TRANSFER_URI,
            $this->makeTransferRequest($secondUserId,$lojistUserId,$amount)
        );

        $secondUserWallet->refresh();
        $lojistWallet = $walletsEloquentRepository->findWalletByUserId($lojistUserId);

        $this->assertEquals($lojistWallet->balance, self::LOJIST_BALANCE + $amount);
        $this->assertEquals($secondUserWallet->balance, self::SECOND_USER_BALANCE);
        $this->assertJson(
            json_encode($secondResponse),
            ExecuteTransferResource::make(['payerWallet' => $secondUserWallet, 'payeeWallet' => $lojistWallet])
        );

    }

    public function testValidateErrorCacheOnRedis(): void
    {
        $cache = make(Cache::class);
        $cache->set(TransactionValidatorServiceStub::ERROR_CACHE_KEY, 'validate');
        $cache->set(TransactionValidator::CACHE_NAME, 0);

        $firstUser = 1;
        $secondUser = 2;
        $lojistUser = 3;
        $amount = 50;

        $walletsEloquentRepository = WalletsEloquentRepository::instantiate();

        $this->post(self::TRANSFER_URI, $this->makeTransferRequest($firstUser,$secondUser, $amount));

        $firstUserWallet = $walletsEloquentRepository->findWalletByUserId($firstUser);
        $secondUserWallet = $walletsEloquentRepository->findWalletByUserId($secondUser);
        $lojistUserWallet = $walletsEloquentRepository->findWalletByUserId($lojistUser);

        $this->assertEquals(self::FIRST_USER_BALANCE, $firstUserWallet->balance);
        $this->assertEquals(self::SECOND_USER_BALANCE, $secondUserWallet->balance);
        $this->assertEquals(self::LOJIST_BALANCE, $lojistUserWallet->balance);

        $cache->delete(TransactionValidatorServiceStub::ERROR_CACHE_KEY);
        $cache->set(TransactionNotificator::CACHE_NAME, 0);
        $cache->set(TransactionNotificatorServiceStub::ERROR_CACHE_KEY, 'sendMessage');

        $this->post(self::TRANSFER_URI, $this->makeTransferRequest($firstUser, $secondUser, $amount));

        $firstUserWallet->refresh();
        $secondUserWallet->refresh();

        $this->assertEquals(self::FIRST_USER_BALANCE - $amount, $firstUserWallet->balance);
        $this->assertEquals(self::SECOND_USER_BALANCE + $amount, $secondUserWallet->balance);

        $validationErrors = $cache->get(TransactionValidator::CACHE_NAME);
        $this->assertEquals(1, $validationErrors);
        $notificationErrors = $cache->get(TransactionNotificator::CACHE_NAME);
        $this->assertEquals(1, $notificationErrors);
        $cache->clear();
    }

    public function testValidateNotificationSent(): void
    {
        $cache = make(Cache::class);

        $firstUser = 1;
        $secondUser = 2;
        $amount = 50;

        $this->post(self::TRANSFER_URI, $this->makeTransferRequest($firstUser, $secondUser, $amount));
        $notificationErrors = $cache->get(TransactionNotificator::CACHE_NAME);
        $this->assertEquals(0, $notificationErrors);
    }

    public function expectFieldRuleTest(array $response, string $field, string $rule, string|array|null $expectedValue): void
    {
        $this->assertArrayHasKey($field, $response);
        $this->assertArrayHasKey($rule, $response[$field]);
        $this->assertEquals($expectedValue, $response[$field][$rule][0] ?? null);
    }
}
