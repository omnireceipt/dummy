<?php

namespace Omnireceipt\Dummy\Http;

use Omnireceipt\Dummy\Exceptions\GatewayException;
use Psr\Http\Message\ResponseInterface;

trait BaseRequestTrait
{
    abstract public function getEndpoint(): string;
    abstract protected function getRequestMethod(): string;

    protected function getRequestUrl(array $queryParams = null): string
    {
        return $this->getEndpoint();
    }

    protected function getRequestHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    protected function request($body): ResponseInterface
    {
        $response = $this->httpClient->request(
            $this->getRequestMethod(),
            $this->getRequestUrl(),
            $this->getRequestHeaders(),
            is_array($body) ? json_encode($body) : $body,
        );

        $statusCode = $response->getStatusCode();
        if (402 === $statusCode) {
            throw new GatewayException('');
        }

        return $response;
    }
}