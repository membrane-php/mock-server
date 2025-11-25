<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\ConfigLocator;

use Membrane\MockServer\Mocking\ConfigLocator;

/**
 * @phpstan-import-type Config from ConfigLocator
 */
final readonly class FromApplicationConfig implements ConfigLocator
{
    /**
     * @param array<string, Config> $operationMap
     */
    public function __construct(
        private array $operationMap,
    ) {}

    /** @return ?Config */
    public function getOperationConfig(string $operationId): ?array
    {
        return $this->operationMap[$operationId] ?? null;
    }
}
