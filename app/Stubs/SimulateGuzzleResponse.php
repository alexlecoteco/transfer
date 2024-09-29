<?php

namespace App\Stubs;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class SimulateGuzzleResponse implements ResponseInterface
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

    public function getProtocolVersion(): string
    {
        // Implement getProtocolVersion() method.
    }

    public function withProtocolVersion($version): MessageInterface
    {
        // Implement withProtocolVersion() method.
    }

    public function getHeaders(): array
    {
        // Implement getHeaders() method.
    }

    public function hasHeader($name): bool
    {
        // Implement hasHeader() method.
    }

    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        // Implement getHeaderLine() method.
    }

    public function withHeader($name, $value): MessageInterface
    {
        // Implement withHeader() method.
    }

    public function withAddedHeader($name, $value): MessageInterface
    {
        // Implement withAddedHeader() method.
    }

    public function withoutHeader($name): MessageInterface
    {
        // Implement withoutHeader() method.
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        // Implement withBody() method.
    }

    public function getStatusCode(): int
    {
        // Implement getStatusCode() method.
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        // Implement withStatus() method.
    }

    public function getReasonPhrase(): string
    {
        // Implement getReasonPhrase() method.
    }
}
