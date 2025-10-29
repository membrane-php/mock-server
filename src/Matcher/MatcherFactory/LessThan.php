<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory;

use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Mocking\Field;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     limit: numeric,
 *     inclusive?: bool,
 * }
 */
final class LessThan implements \Membrane\MockServer\Matcher\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        $limit = $config['limit'];
        if (is_string($limit)) {
            $limit = (float) $limit;
        }

        return new \Membrane\MockServer\Matcher\Matcher\LessThan(
            Field::fromArray($config['field']),
            $limit,
            $config['inclusive'] ?? true,
        );
    }
}
