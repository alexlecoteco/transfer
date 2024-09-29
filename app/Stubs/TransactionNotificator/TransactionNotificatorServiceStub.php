<?php

namespace App\Stubs\TransactionNotificator;

use App\Traits\InstantiateTrait;
use Exception;
use Hyperf\Cache\Cache;

class TransactionNotificatorServiceStub
{
    use InstantiateTrait;

    public const ERROR_CACHE_KEY = 'transaction_notificator_error';
    public function __construct(private Cache $cache)
    {
    }

    private function shouldFail(string $functionName): void
    {
        if ($this->cache->get(self::ERROR_CACHE_KEY) === $functionName) {
            throw new Exception('custom_generic_error');
        }
    }

    /**
     * @throws Exception
     */
    public function sendMessage(): array
    {
        $this->shouldFail('sendMessage');
        return [];
    }
}
