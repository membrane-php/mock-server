<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture;

use Membrane\MockServer\Matcher\Matcher;

final readonly class MatcherFactory implements \Membrane\MockServer\Matcher\MatcherFactory
{
    /** @param array<mixed> $expects */
    public function __construct(
        private array $expects,
        private Matcher $creates,
    ) {}

    /**
     * @param array<mixed> $config
     * @throws Exception\FailedExpectation if config does not match expected
     */
    public function create(array $config): Matcher
    {
        if ($config !== $this->expects) {
            throw new Exception\FailedExpectation(sprintf(
                <<<MESSAGE
                Unexpected config.

                Expected:
                %s

                Actual:
                %s
                MESSAGE,
                json_encode($this->expects, JSON_PRETTY_PRINT),
                json_encode($config, JSON_PRETTY_PRINT),
            ));
        }

        return $this->creates;
    }
}
