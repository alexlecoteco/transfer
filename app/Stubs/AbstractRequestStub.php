<?php

namespace App\Stubs;

abstract class AbstractRequestStub extends ClientStubRequest
{
    public function __call(string $method, array $arguments): SimulateGuzzleResponse
    {
        $uri = $arguments[0];
        $parameters = $arguments[1] ?? [];

        $response = match ($method) {
            'post' => $this->handlePostRequest($uri, $parameters),
            default => $this->handleGetRequest($uri, $parameters),
        };

        if (isset($response['stub-headers'])) {
            return SimulateGuzzleResponse::create($response['body'], $response['stub-headers']);
        }

        return SimulateGuzzleResponse::create($response);
    }

    abstract protected function handleGetRequest(string $uri, array $parameters);

    abstract protected function handlePostRequest(string $uri, array $parameters);
}
