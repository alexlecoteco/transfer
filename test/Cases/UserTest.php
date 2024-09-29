<?php

namespace HyperfTest\Cases;

use App\Model\Users;
use HyperfTest\AbstractTest;

class UserTest extends AbstractTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->freshStart();
        $this->createTestingModels();
    }

    public function testInvalidateUserWithSameDocument(): void
    {
        $failedUser = Users::insertOrIgnore(
            [
                'name' => self::FIRST_TEST_USER,
                'email' => 'other@email.com.br',
                'document' => self::FIRST_TEST_USER_DOCUMENT,
                'password' => 'asdf'
            ]
        );

        $this->assertEquals(0, $failedUser);
    }

    public function testInvalidateUserWithSameEmail(): void
    {
        $failedUser = Users::insertOrIgnore(
            [
                'name' => self::FIRST_TEST_USER,
                'email' => self::FIRST_TEST_USER . '@test.com.br',
                'document' => self::FIRST_TEST_USER_DOCUMENT + 1,
                'password' => 'asdf'
            ]
        );

        $this->assertEquals(0, $failedUser);
    }
}
