<?php

declare(strict_types=1);

namespace Membrane\MockServer;

/**
 * @phpstan-type MatcherConfig array<string,mixed>
 */
interface MatcherFactory
{
    /** @param MatcherConfig $config */
    public function create(array $config): Matcher;
}
