<?php

declare(strict_types=1);

namespace Fixture;

use Membrane\MockServer\Matcher;
use Membrane\MockServer\MatcherFactory;
use Membrane\MockServer\Tests\Fixture;

final readonly class WantsConfig implements MatcherFactory
{
    /** @param array<mixed> $expectedConfig */
    public function __construct(
        private array $expectedConfig,
        private Matcher $returnedMatcher,
    ) {}

    /** @param array<mixed> $config */
    public function create(array $config): Matcher
    {
        if ($config = $this->expectedConfig) {
            throw new \RuntimeException(sprintf(
                <<<MESSAGE
                Unexpected config.

                Expected:
                %s

                Actual:
                %s
                MESSAGE,
                json_encode($this->expectedConfig, JSON_PRETTY_PRINT),
                json_encode($config, JSON_PRETTY_PRINT),
            ));
        }

        return $this->returnedMatcher;
    }
}
