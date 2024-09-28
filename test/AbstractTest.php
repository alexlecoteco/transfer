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
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mockRequests();
    }

    public function setUp(): void
    {
        Schema::disableForeignKeyConstraints();
        $table = Db::select('SHOW TABLES');
        foreach ($table as $name) {
            if ($name = 'migrations') {
                continue;
            }

            Db::table($name)->truncate();
        }
        Schema::enableForeignKeyConstraints();
//        $this->createTestingModels();
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

    private function createTestingModels(): void
    {
        $userTypeLojist = UsersTypes::create(
            ['name' => UserTypesEnum::LOJIST->value]
        );
        $userTypeCommon = UsersTypes::create(
            ['name' => UserTypesEnum::COMMON->value]
        );
        $firstUser = Users::create(
            [
                'name' => 'firstTest',
                'email' => 'firstTest@test.com.br',
                'document' => 1234,
                'password' => 'asdf',
                'user_type' => $userTypeCommon->id
            ]
        );

        $secondUser = Users::create(
            [
                'name' => 'secondTest',
                'email' => 'secondTest@test.com.br',
                'document' => 5678,
                'password' => 'asdf',
                'user_type' => $userTypeCommon->id
            ]
        );

        $thirdUser = Users::create(
            [
                'name' => 'lojistTest',
                'email' => 'lojistTest@test.com.br',
                'document' => 1357,
                'password' => 'asdf',
                'user_type' => $userTypeLojist->id
            ]
        );

        Wallets::create(
            [
                'user_id' => $firstUser->id,
                'balance' => 100
            ]
        );

        Wallets::create(
            [
                'user_id' => $secondUser->id,
                'balance' => 500
            ]
        );

        Wallets::create(
            [
                'user_id' => $thirdUser->id,
                'balance' => 0
            ]
        );
    }
}
