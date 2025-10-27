<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory\Array;

use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Mocking\Field;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     values: list<mixed>,
 * }
 */
final class Contains implements \Membrane\MockServer\Matcher\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        return new \Membrane\MockServer\Matcher\Matcher\Array\Contains(
            Field::fromConfig($config['field']),
            ...$config['values'],
        );
    }
}
