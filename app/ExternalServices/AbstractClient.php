<?php

namespace App\ExternalServices;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Hyperf\Cache\Cache;
use Hyperf\Context\ApplicationContext;
use Hyperf\Guzzle\HandlerStackFactory;
use function Hyperf\Support\make;

class AbstractClient
{
    public function __construct(protected Cache $cache)
    {
    }

    protected const TIME_OUT_IN_SECONDS = 5;
    protected array $swooleConfig = [
        'timeout' => self::TIME_OUT_IN_SECONDS,
        'socket_buffer_size' => 1024 * 1024 * 2
    ];
    public function getClient(): ClientInterface
    {
        $container = ApplicationContext::getContainer();
        $stack = $container->get(HandlerStackFactory::class)->create();
        return make(
            Client::class,
            [
                'handler' => $stack,
                'timeout' => self::TIME_OUT_IN_SECONDS,
                'swoole' => $this->swooleConfig
            ]
        );
    }
}
