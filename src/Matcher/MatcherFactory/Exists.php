<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory;

use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Mocking\Field;

/**
 * @phpstan-type Config array{
 *     fields: non-empty-list<non-empty-list<string>>,
 * }
 */
final class Exists implements \Membrane\MockServer\Matcher\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        return new \Membrane\MockServer\Matcher\Matcher\Exists(...array_map(
            fn($f) => Field::fromArray($f),
            $config['fields'],
        ));
    }
}
