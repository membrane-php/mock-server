<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory;

use Membrane\Attribute\Builder;
use Membrane\Attribute\ClassWithAttributes;
use Membrane\Membrane;
use Membrane\MockServer\Exception\InvalidConfig;
use Membrane\MockServer\Matcher\Matcher;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     limit: numeric,
 *     inclusive?: bool,
 * }
 */
final class GreaterThan implements \Membrane\MockServer\Matcher\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        $result = (new Membrane(new Builder()))
            ->process($config, new ClassWithAttributes(Matcher\GreaterThan::class));

        if (! $result->isValid()) {
            throw InvalidConfig::fromResult($result);
        }

        assert($result->value instanceof Matcher\GreaterThan);
        return $result->value;
    }
}
