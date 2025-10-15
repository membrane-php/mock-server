<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Mocking\Module
 */
final readonly class ConfigLocator implements \Membrane\MockServer\Mocking\ConfigLocator
{
    /** @param array<string, OperationConfig> $config */
    public function __construct(private array $config) {}

    /** @return ?OperationConfig */
    public function getOperationConfig(string $operationId): ?array
    {
        return $this->config[$operationId] ?? null;
    }
}
