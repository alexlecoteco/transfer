<?php

namespace App\ExternalServices\TransactionValidator;

use App\ExternalServices\AbstractClient;
use App\Traits\CacheFailuresTrait;
use App\Traits\InstantiateTrait;
use Throwable;


class TransactionValidator extends AbstractClient implements TransactionValidatorInterface
{
    use InstantiateTrait, CacheFailuresTrait;

    public const CACHE_NAME = 'validation_exception';
    /**
     * @throws Throwable
     */
    public function validate(): void
    {
        try {
            $this->getClient()->get('https://util.devi.tools/api/v2/authorize');
        } catch (Throwable $exception) {
            $this->cacheFailures(self::CACHE_NAME, 600);
            throw $exception;
        }
    }
}
