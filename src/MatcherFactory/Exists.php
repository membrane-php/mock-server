<?php

declare(strict_types=1);

namespace Membrane\MockServer\MatcherFactory;

use Membrane\MockServer\Field;
use Membrane\MockServer\Matcher;

/**
 * @phpstan-type Config array{
 *     fields: non-empty-list<non-empty-list<string>>,
 * }
 */
final class Exists implements \Membrane\MockServer\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        return new Matcher\Exists(...array_map(
            fn($f) => Field::fromConfig($f),
            $config['fields'],
        ));
    }
}
