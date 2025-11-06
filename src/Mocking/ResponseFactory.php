<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-import-type ResponseConfig from \Membrane\MockServer\Mocking\Module
 */
final readonly class ResponseFactory
{
    /** @param ResponseConfig|int $config */
    public function create(array|int $config): ResponseInterface
    {
        if (is_int($config)) {
            return new Response($config);
        }

        return new Response(
            $config['code'],
            $config['headers'] ?? [],
            $this->getResponseBody($config['body'] ?? ''),
        );
    }

    /** @param mixed[]|string $body */
    private function getResponseBody(array|string $body): string
    {
        if (is_string($body)) {
            return $body;
        }

        return json_encode($body)
            ?: throw new \RuntimeException(json_last_error_msg());
    }
}
