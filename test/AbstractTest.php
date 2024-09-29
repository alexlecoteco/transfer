<?php

namespace HyperfTest;


use App\Enums\UserTypesEnum;
use App\ExternalServices\TransactionNotificator\TransactionNotificator;
use App\ExternalServices\TransactionValidator\TransactionValidator;
use App\Model\Users;
use App\Model\UsersTypes;
use App\Model\Wallets;
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
        $userTypeLojist = UsersTypes::create(
            ['name' => UserTypesEnum::LOJIST->value]
        );
        $userTypeCommon = UsersTypes::create(
            ['name' => UserTypesEnum::COMMON->value]
        );
        $firstUser = Users::create(
            [
                'name' => self::FIRST_TEST_USER,
                'email' => self::FIRST_TEST_USER . '@test.com.br',
                'document' => self::FIRST_TEST_USER_DOCUMENT,
                'password' => 'asdf',
                'user_type' => $userTypeCommon->id
            ]
        );

        $secondUser = Users::create(
            [
                'name' => self::SECOND_TEST_USER_NAME,
                'email' => self::SECOND_TEST_USER_NAME . 'secondTest@test.com.br',
                'document' => self::SECOND_TEST_USER_DOCUMENT,
                'password' => 'asdf',
                'user_type' => $userTypeCommon->id
            ]
        );

        $thirdUser = Users::create(
            [
                'name' => self::LOJIST_USER,
                'email' => self::LOJIST_USER . '@test.com.br',
                'document' => self::LOJIST_USER_PASSWORD,
                'password' => 'asdf',
                'user_type' => $userTypeLojist->id
            ]
        );

        Wallets::create(
            [
                'user_id' => $firstUser->id,
                'balance' => self::FIRST_USER_BALANCE
            ]
        );

        Wallets::create(
            [
                'user_id' => $secondUser->id,
                'balance' => self::SECOND_USER_BALANCE
            ]
        );

        Wallets::create(
            [
                'user_id' => $thirdUser->id,
                'balance' => self::LOJIST_BALANCE
            ]
        );
    }
}
