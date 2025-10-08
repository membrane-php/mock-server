<?php

declare(strict_types=1);

namespace Membrane\MockServer;

/**
 * @phpstan-import-type MatcherConfig from \Membrane\MockServer\Module
 */
interface MatcherFactory
{
    /** @param MatcherConfig $config */
    public function create(array $config): Matcher;
}
