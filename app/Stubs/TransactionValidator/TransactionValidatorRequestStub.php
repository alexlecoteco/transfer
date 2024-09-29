<?php

namespace App\Stubs\TransactionValidator;

use App\Stubs\AbstractRequestStub;
use Exception;

class TransactionValidatorRequestStub extends AbstractRequestStub
{

    /**
     * @throws Exception
     */
    protected function handleGetRequest(string $uri, array $parameters)
    {

        return TransactionValidatorServiceStub::instantiate()->validate();
    }

    protected function handlePostRequest(string $uri, array $parameters)
    {
        return [];
    }
}
