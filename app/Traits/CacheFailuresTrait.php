<?php

namespace App\Traits;

use Hyperf\Cache\Cache;
use function Hyperf\Support\make;

trait CacheFailuresTrait
{
    private function cacheFailures(string $key, int $ttl = 60): void
    {
        $cache = make(Cache::class);
        $pastFailures = $cache->get($key, 0);
        $cache->set('failed_notify', $pastFailures + 1, $ttl);
    }
}
