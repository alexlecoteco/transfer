<?php

namespace App\Stubs\TransactionNotificator;

use App\Stubs\AbstractRequestStub;
use Exception;

class TransactionNotificatorRequestStub extends AbstractRequestStub
{

    protected function handleGetRequest(string $uri, array $parameters)
    {
        return [];
    }

    /**
     * @throws Exception
     */
    protected function handlePostRequest(string $uri, array $parameters)
    {
        return TransactionNotificatorServiceStub::instantiate()->sendMessage();
    }
}
