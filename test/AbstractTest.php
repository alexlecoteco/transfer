<?php

namespace HyperfTest;


use App\Enums\UserTypesEnum;
use App\ExternalServices\TransactionNotificator\TransactionNotificator;
use App\ExternalServices\TransactionValidator\TransactionValidator;
use App\Repositories\Users\UsersEloquentRepository;
use App\Repositories\UserTypes\UserTypesEloquentRepository;
use App\Repositories\Wallets\WalletsEloquentRepository;
use App\Stubs\TransactionNotificator\TransactionNotificatorRequestStub;
use App\Stubs\TransactionValidator\TransactionValidatorRequestStub;
use Hyperf\Context\ApplicationContext;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;
use Mockery;
use function Hyperf\Support\make;

class AbstractTest extends HttpTestCase
{
    public const FIRST_TEST_USER = 'firstTest';
    public const FIRST_TEST_USER_DOCUMENT = 1234;
    public const SECOND_TEST_USER_NAME = 'secondTest';
    public const SECOND_TEST_USER_DOCUMENT = 5678;
    public const LOJIST_USER = 'lojistTest';
    public const LOJIST_USER_PASSWORD = 1357;
    public const FIRST_USER_BALANCE = 100;
    public const SECOND_USER_BALANCE = 500;
    public const LOJIST_BALANCE = 1000;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mockRequests();
    }

    public function testClassTest(): void
    {
        $this->assertTrue(true);
    }

    public function mockRequests(): void
    {
        $mockedClass = Mockery::mock(TransactionNotificator::class)
            ->shouldReceive('getClient')
            ->andReturnUsing(function () {
                return make(TransactionNotificatorRequestStub::class);
            });

        $container = ApplicationContext::getContainer();
        $container->define(TransactionNotificator::class, fn () => $mockedClass->getMock()->makePartial());

        $secondMockedClass = Mockery::mock(TransactionValidator::class)
            ->shouldReceive('getClient')
            ->andReturnUsing(function () {
                return make(TransactionValidatorRequestStub::class);
            });

        $container = ApplicationContext::getContainer();
        $container->define(TransactionValidator::class, fn () => $secondMockedClass->getMock()->makePartial());
    }

    public function freshStart(): void
    {
        Schema::disableForeignKeyConstraints();
        $table = json_decode(json_encode(Db::select('SHOW TABLES')), true);
        foreach ($table as $name) {
            $tableName = $name['Tables_in_testing'];
            if ($tableName !== 'migrations') {
                Db::table($tableName)->truncate();
            }
        }
        Schema::enableForeignKeyConstraints();
    }

    protected function createTestingModels(): void
    {
        $userEloquentRepository = UsersEloquentRepository::instantiate();
        $walletsEloquentRepository = WalletsEloquentRepository::instantiate();
        $userTypeEloquentRepository = UserTypesEloquentRepository::instantiate();

        $userTypeLojist = $userTypeEloquentRepository->createUserType(
            UserTypesEnum::LOJIST->value
        );
        $userTypeCommon = $userTypeEloquentRepository->createUserType(
            UserTypesEnum::COMMON->value
        );

        $firstUser = $userEloquentRepository->createUser(
                self::FIRST_TEST_USER,
                self::FIRST_TEST_USER . '@test.com.br',
                self::FIRST_TEST_USER_DOCUMENT,
                'asdf',
                $userTypeCommon->id
        );

        $secondUser = $userEloquentRepository->createUser(
                self::SECOND_TEST_USER_NAME,
                self::SECOND_TEST_USER_NAME . 'secondTest@test.com.br',
                self::SECOND_TEST_USER_DOCUMENT,
                'asdf',
                $userTypeCommon->id
        );

        $thirdUser = $userEloquentRepository->createUser(
                self::LOJIST_USER,
                self::LOJIST_USER . '@test.com.br',
                self::LOJIST_USER_PASSWORD,
                'asdf',
                $userTypeLojist->id
        );

        $walletsEloquentRepository->createWallet(
                $firstUser->id,
                self::FIRST_USER_BALANCE
        );

        $walletsEloquentRepository->createWallet(
                $secondUser->id,
                self::SECOND_USER_BALANCE
        );

        $walletsEloquentRepository->createWallet(
                $thirdUser->id,
                self::LOJIST_BALANCE
        );
    }
}
