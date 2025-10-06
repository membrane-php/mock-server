<?php

declare(strict_types=1);

namespace Membrane\MockServer;

/**
 * @phpstan-type MatcherConfig array{
 *     parameters?: array<string,mixed>,
 *     type: string,
 * }
 */
interface MatcherFactory
{
    /** @param MatcherConfig $config */
    public function create(array $config): Matcher;
}
