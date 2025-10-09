<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\MatcherFactory;

use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     value: mixed,
 * }
 */
final class Equals implements \Membrane\MockServer\Mocking\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        return new Matcher\Equals(
            Field::fromConfig($config['field']),
            $config['value'],
        );
    }
}
