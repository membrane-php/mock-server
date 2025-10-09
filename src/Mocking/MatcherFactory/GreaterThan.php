<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\MatcherFactory;

use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     limit: numeric,
 *     inclusive?: bool,
 * }
 */
final class GreaterThan implements \Membrane\MockServer\Mocking\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        $limit = $config['limit'];
        if (is_string($limit)) {
            $limit = (float) $limit;
        }

        return new Matcher\GreaterThan(
            Field::fromConfig($config['field']),
            $limit,
            $config['inclusive'] ?? true,
        );
    }
}
