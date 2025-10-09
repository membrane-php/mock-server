<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\MatcherFactory;

use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher;

/**
 * @phpstan-type Config array{
 *     fields: non-empty-list<non-empty-list<string>>,
 * }
 */
final class Exists implements \Membrane\MockServer\Mocking\MatcherFactory
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
