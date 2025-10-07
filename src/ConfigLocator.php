<?php

declare(strict_types=1);

namespace Membrane\MockServer;

/**
 * @phpstan-import-type FactoryConfig from FactoryLocator
 * @phpstan-import-type ResponseConfig from ResponseFactory
 *
 *
 * @phpstan-type OperationConfig array{
 *      matchers?: list<array{matcher: FactoryConfig, response: ResponseConfig}>,
 *      default?: array{response: ResponseConfig}
 *  }
 */
interface ConfigLocator
{
    /** @return OperationConfig */
    public function getOperationConfig(string $operationId): ?array;
}
