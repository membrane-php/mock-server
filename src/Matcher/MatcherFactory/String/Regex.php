<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory\String;

use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Mocking\Field;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     pattern: string,
 * }
 */
final class Regex implements \Membrane\MockServer\Matcher\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        return new \Membrane\MockServer\Matcher\Matcher\String\Regex(
            Field::fromArray($config['field']),
            $config['pattern'],
        );
    }
}
