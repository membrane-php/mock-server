<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory;

use Membrane\MockServer\Exception\InvalidConfig;
use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Mocking\Field;

use const Membrane\MockServer\Mocking\MatcherFactory\array_unshi;

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
        array_unshi
        return new \Membrane\MockServer\Matcher\Matcher\Equals(
            Field::fromConfig($config['field']),
            $config['value'],
        );
    }

    public function validate(array $config): void
    {
        if (!(
            isset($config['field'])
            && is_array($config['field'])
            && !array_is_list($config['field'])
            && array_reduce(
                $config['field'],
                fn($previousAreString, $current) => $previousAreString && is_string($current),
                true
            )
        )) {
            throw InvalidConfig::field([self::class, 'field']);
        }
    }

}
