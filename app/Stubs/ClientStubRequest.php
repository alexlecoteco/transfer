<?php

namespace App\Stubs;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClientStubRequest implements ClientInterface
{

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        // Implement send() method.
    }

    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        // Implement sendAsync() method.
    }

    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        // Implement request() method.
    }

    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        // Implement requestAsync() method.
    }

    public function getConfig(?string $option = null)
    {
        // Implement getConfig() method.
    }
}
