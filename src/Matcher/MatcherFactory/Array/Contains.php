<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory\Array;

use Membrane\Attribute\Builder;
use Membrane\Attribute\ClassWithAttributes;
use Membrane\Membrane;
use Membrane\MockServer\Exception\InvalidConfig;
use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Mocking\Field;

/**
 * @phpstan-import-type Config from Matcher\Array\Contains
 */
final class Contains implements \Membrane\MockServer\Matcher\MatcherFactory
{
    /** @param Config $config */
    public function create(array $config): Matcher
    {
        $result = (new Membrane(new Builder()))
            ->process($config, new ClassWithAttributes(Matcher\Array\Contains::class));

        if (! $result->isValid()) {
            throw InvalidConfig::fromResult($result);
        }

        assert ($result->value instanceof Matcher\Array\Contains);
        return $result->value;
    }
}
