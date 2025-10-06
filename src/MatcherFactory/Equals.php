<?php

declare(strict_types=1);

namespace Membrane\MockServer\MatcherFactory;

use Membrane\MockServer\Field;
use Membrane\MockServer\Matcher;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     value: mixed,
 * }
 */
final class Equals implements \Membrane\MockServer\MatcherFactory
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
