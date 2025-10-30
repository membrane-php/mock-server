<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory\Array;

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
        return new Matcher\Array\Contains(
            Field::fromArray($config['field']),
            ...$config['values'],
        );
    }
}
