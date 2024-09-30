<?php

namespace HyperfTest\Cases;

use App\Enums\UserTypesEnum;
use App\Model\UserTypes;
use HyperfTest\AbstractTest;

class UserTypesTest extends AbstractTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->freshStart();
        $this->createTestingModels();
    }

    public function testInvalidateUserTypeWithSameName(): void
    {
        $failedInsertion = UserTypes::insertOrIgnore(
            ['name' => UserTypesEnum::COMMON->value]
        );

        $this->assertEquals(0, $failedInsertion);
    }
}
