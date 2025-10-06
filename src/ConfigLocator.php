<?php

declare(strict_types=1);

namespace Membrane\MockServer;

/**
 * @phpstan-import-type MatcherConfig from MatcherFactory
 * @phpstan-import-type ResponseConfig from ResponseFactory
 *
 * @phpstan-type OperationConfig array{
 *     matchers?: list<array{matcher: MatcherConfig, response: ResponseConfig}>,
 *     default?: array{response: ResponseConfig}
 * }
 */
final readonly class ConfigLocator
{
    /** @param array<string, OperationConfig> $operationMap */
    public function __construct(
        private array $operationMap,
    ) {}

    /** @return ?OperationConfig */
    public function getOperationConfig(string $operationId): ?array
    {
        return $this->operationMap[$operationId] ?? null;
    }
}
