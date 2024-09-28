<?php

namespace App\ExternalServices\TransactionValidator;

use App\ExternalServices\AbstractClient;
use App\Traits\CacheFailuresTrait;
use App\Traits\InstantiateTrait;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Di\Exception\Exception;


class TransactionValidator extends AbstractClient implements TransactionValidatorInterface
{
    use InstantiateTrait, CacheFailuresTrait;

    private const CACHE_NAME = 'validation_exception';
    /**
     * @throws GuzzleException
     */
    public function validate(): void
    {
        try {
            $this->getClient()->get('https://util.devi.tools/api/v2/authorize');
        } catch (GuzzleException $exception) {
            $this->cacheFailures(self::CACHE_NAME, 600);
            throw $exception;
        }
    }
}
