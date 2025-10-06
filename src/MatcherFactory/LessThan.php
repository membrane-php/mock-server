<?php

declare(strict_types=1);

namespace Membrane\MockServer\MatcherFactory;

use Membrane\MockServer\Field;
use Membrane\MockServer\Matcher;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     limit: numeric,
 *     inclusive?: bool,
 * }
 */
final class LessThan implements \Membrane\MockServer\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        $limit = $config['limit'];
        if (is_string($limit)) {
            $limit = (float) $limit;
        }

        return new Matcher\LessThan(
            Field::fromConfig($config['field']),
            $limit,
            $config['inclusive'] ?? true,
        );
    }
}
