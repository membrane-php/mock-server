<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\ConfigLocator;

use Membrane\MockServer\Mocking\ConfigLocator;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Mocking\Module
 */
final readonly class FromApplicationConfig implements ConfigLocator
{
    /** @param array<string, OperationConfig> $operationMap */
    public function __construct(
        private array $operationMap,
    ) {}

    public function getOperationConfig(string $operationId): ?array
    {
        return $this->operationMap[$operationId] ?? null;
    }
}
