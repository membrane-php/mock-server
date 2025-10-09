<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking;

/**
 * @phpstan-import-type MatcherConfig from \Membrane\MockServer\Mocking\Module
 */
interface MatcherFactory
{
    /** @param MatcherConfig $config */
    public function create(array $config): Matcher;
}
