<?php

declare(strict_types=1);

namespace Membrane\MockServer\ConfigLocator;

use Membrane\MockServer\ConfigLocator;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Module
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
