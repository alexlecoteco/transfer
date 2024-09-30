<?php

namespace App\Stubs;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class SimulateGuzzleResponse implements ClientInterface
{
    private string $response;
    private array $headers;

    public function __construct(array $response, array $headers)
    {
        $this->response = json_encode($response);
        $this->headers = $headers;
    }

    public static function create(array $response, array $headers = []): SimulateGuzzleResponse
    {
        return new self($response, $headers);
    }

    public function getBody(): SimulateGuzzleStreamResponse
    {
        return new SimulateGuzzleStreamResponse($this->response);
    }

    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        // TODO: Implement send() method.
    }

    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        // TODO: Implement sendAsync() method.
    }

    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        // TODO: Implement request() method.
    }

    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        // TODO: Implement requestAsync() method.
    }

    public function getConfig(?string $option = null)
    {
        // TODO: Implement getConfig() method.
    }
}
