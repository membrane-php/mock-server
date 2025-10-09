<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\MatcherFactory\Array;

use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     values: list<mixed>,
 * }
 */
final class Contains implements \Membrane\MockServer\Mocking\MatcherFactory
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
