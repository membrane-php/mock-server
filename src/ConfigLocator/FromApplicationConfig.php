<?php

declare(strict_types=1);

namespace Membrane\MockServer\ConfigLocator;

use Membrane\MockServer\ConfigLocator;
use Membrane\MockServer\FactoryLocator;
use Membrane\MockServer\MatcherFactory;
use Membrane\MockServer\ResponseFactory;

/**
 * @phpstan-import-type FactoryConfig from FactoryLocator
 * @phpstan-import-type ResponseConfig from ResponseFactory
 *
 * @phpstan-type OperationMap array<string, OperationConfig>
 * @phpstan-type OperationConfig array{
 *     matchers?: list<array{matcher: FactoryConfig, response: ResponseConfig}>,
 *     default?: array{response: ResponseConfig}
 * }
 */
final readonly class FromApplicationConfig implements ConfigLocator
{
    /** @param OperationMap $operationMap */
    public function __construct(
        private array $operationMap,
    ) {}

    /** @return ?OperationConfig */
    public function getOperationConfig(string $operationId): ?array
    {
        return $this->operationMap[$operationId] ?? null;
    }
}
