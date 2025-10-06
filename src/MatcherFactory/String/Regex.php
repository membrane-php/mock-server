<?php

declare(strict_types=1);

namespace Membrane\MockServer\MatcherFactory\String;

use Membrane\MockServer\Field;
use Membrane\MockServer\Matcher;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     pattern: string,
 * }
 */
final class Regex implements \Membrane\MockServer\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        return new Matcher\String\Regex(
            Field::fromConfig($config['field']),
            $config['pattern'],
        );
    }
}
