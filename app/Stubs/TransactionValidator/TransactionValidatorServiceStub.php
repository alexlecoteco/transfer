<?php

namespace App\Stubs\TransactionValidator;

use App\ExternalServices\TransactionValidator\TransactionValidator;
use App\Traits\CacheFailuresTrait;
use App\Traits\InstantiateTrait;
use Exception;
use Hyperf\Cache\Cache;

class TransactionValidatorServiceStub
{
    use InstantiateTrait, CacheFailuresTrait;

    public const ERROR_CACHE_KEY = 'transaction_validator_error';
    public function __construct(private Cache $cache)
    {
    }

    private function shouldFail(string $functionName): void
    {
        if ($this->cache->get(self::ERROR_CACHE_KEY, '') === $functionName) {
            throw new Exception('custom_generic_error');
        }
    }

    /**
     * @throws Exception
     */
    public function validate(): array
    {
        $this->shouldFail('validate');
        return [];
    }
}
