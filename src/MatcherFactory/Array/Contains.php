<?php

declare(strict_types=1);

namespace Membrane\MockServer\MatcherFactory\Array;

use Membrane\MockServer\Field;
use Membrane\MockServer\Matcher;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     values: list<mixed>,
 * }
 */
final class Contains implements \Membrane\MockServer\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        return new Matcher\Array\Contains(
            Field::fromConfig($config['field']),
            ...$config['values'],
        );
    }
}
