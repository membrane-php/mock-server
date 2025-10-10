<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Mocking\Module
 */
interface ConfigLocator
{
    /** @return OperationConfig */
    public function getOperationConfig(string $operationId): ?array;
}
