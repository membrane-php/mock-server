<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-import-type ResponseConfig from \Membrane\MockServer\Module
 * @phpstan-import-type ResponseBodyConfig from \Membrane\MockServer\Module
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

    /** @param ResponseBodyConfig|string $body */
    private function getResponseBody(array|string $body): string
    {
        if (is_string($body)) {
            return $body;
        }

        switch ($body['type']) {
            case 'application/json':
                return json_encode($body['content'])
                    ?: throw new \RuntimeException(json_last_error_msg());
            default:
                throw new \RuntimeException(<<<MESSAGE
                    Encoding to "{$body['type']}" is currently unsupported.
                    Instead, pass as a (already encoded) string.
                    MESSAGE);
        }
    }
}
