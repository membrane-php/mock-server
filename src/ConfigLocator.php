<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-type OperationConfig array{
 *      matchers: list<array{response: ResponseConfig, matcher: MatcherConfig}>,
 *      default: array{response: ResponseConfig}
 *  }
 * @phpstan-type OperationConfig array{
 *     matchers: list<array{response: ResponseConfig, matcher: MatcherConfig}>,
 *     default: array{response: ResponseConfig}
 * }
 * @phpstan-type ResponseBody string|array{content: array<mixed>, type: string}
 * @phpstan-type ResponseConfig int|array{
 *     code: int,
 *     headers?: array{string, string},
 *     body?: ResponseBody
 * }
 * @phpstan-type MatcherConfig array{0: class-string<Matcher>, ...mixed}
 */
final readonly class ConfigLocator
{
    /**
     * @param array{
     *     matcherAliases: array<string, class-string<Matcher>>,
     *     operationMap: array<string, OperationConfig>,
     * } $config
     */
    public function __construct(
        private array $config,
    ) {}

    /**
     * @param string $operationId
     *
     * @return null|array{
     *     matchers: array{matcher: MatcherConfig, response: ResponseInterface},
     *     default: array{response: ResponseInterface},
     * }
     */
    public function getOperationConfig(string $operationId): ?array
    {
        if (!isset($this->config[$operationId])) {
            return null;
        }
        $operationConfig = $this->config[$operationId];

        return [
            'matchers' => array_map(
                fn($matchCase) => [
                    'matcher' => $this
                        ->getMatcher($matchCase['matcher']),
                    'response' => $this
                        ->getResponse($matchCase['response']),
                ],
                $operationConfig['matchers'] ?? [],
            ),
            'default' => [
                'response' => $this
                    ->getResponse($operationConfig['default']['response']),
            ],
        ];
    }

    /** @param MatcherConfig $matcherConfig */
    private function getMatcher(Matcher $matcherConfig): Matcher
    {
        return $matcherConfig;
    }

    /** @param ResponseConfig $responseConfig */
    private function getResponse(array|int $responseConfig): ResponseInterface
    {
        if (is_int($responseConfig)) {
            return new Response($responseConfig);
        }

        return new Response(
            $responseConfig['code'],
            $responseConfig['headers'] ?? [],
            $this->getResponseBody($responseConfig['body'] ?? ''),
        );
    }

    /** @param ResponseBody $body */
    private function getResponseBody(array|string $body): string
    {
        if (is_string($body)) {
            return $body;
        }

        return match ($body['type']) {
            'application/json' => json_encode($body['content']),
            default => throw new \RuntimeException(<<<MESSAGE
                Encoding arrays to "{$body['type']}" is not supported, currently.
                Instead, pass as an, already encoded, string
                MESSAGE),
        };
    }
}
