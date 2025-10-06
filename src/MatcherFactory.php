<?php

declare(strict_types=1);

namespace Membrane\MockServer;

/**
 * @phpstan-import-type MatcherConfig from ConfigLocator
 */
interface MatcherFactory
{
    public function create(array $config): Matcher;
}
