<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\Exception\InvalidConfig;

/**
 * @phpstan-import-type MatcherConfig from \Membrane\MockServer\Mocking\Module
 */
interface MatcherFactory
{
    /** @param MatcherConfig $config */
    public function create(array $config): Matcher;

    /**
     * @param MatcherConfig $config
     * @throws InvalidConfig
     */
    public function validate(array $config): void;
}
