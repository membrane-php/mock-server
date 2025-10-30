<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory;

use Membrane\MockServer\Exception\InvalidConfig;
use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Mocking\Field;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     value: mixed,
 * }
 */
final class Equals implements \Membrane\MockServer\Matcher\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        return new \Membrane\MockServer\Matcher\Matcher\Equals(
            Field::fromArray($config['field']),
            $config['value'],
        );
    }
}
