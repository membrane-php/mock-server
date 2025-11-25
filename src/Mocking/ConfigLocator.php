<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking;

/**
 * @phpstan-import-type ResponseConfig from \Membrane\MockServer\Mocking\Module
 * @phpstan-import-type MatcherFactoryConfig from \Membrane\MockServer\Matcher\Module
 *
 * @phpstan-type Config array{
 *      operationId: string,
 *      default: array{response: ResponseConfig},
 *      matchers?: list<array{
 *          matcher: MatcherFactoryConfig,
 *          response: ResponseConfig,
 *      }>
 *  }
 */
interface ConfigLocator
{
    /** @return Config */
    public function getOperationConfig(string $operationId): ?array;
}
