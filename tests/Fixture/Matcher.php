<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture\Matcher;

final readonly class Matcher implements \Membrane\MockServer\Matcher
{
    public function __construct(
        private bool $matches,
    ) {}

    public function matches(\Membrane\MockServer\DTO $dto): true
    {
        return $this->matches;
    }
}
