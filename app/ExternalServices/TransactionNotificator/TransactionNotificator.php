<?php

namespace App\ExternalServices\TransactionNotificator;

use App\ExternalServices\AbstractClient;
use App\Traits\CacheFailuresTrait;
use App\Traits\InstantiateTrait;
use GuzzleHttp\Exception\GuzzleException;

class TransactionNotificator extends AbstractClient implements TransactionNotificatorInterface
{
    use InstantiateTrait, CacheFailuresTrait;
    private const CACHE_NAME = 'failed_notify';
    public function notify(): bool
    {
        try {
            $this->getClient()->post('https://util.devi.tools/api/v1/notify');
            return true;
        } catch (GuzzleException) {
            $this->cacheFailures(self::CACHE_NAME);
            return false;
        }
    }
}
