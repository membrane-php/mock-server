<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\Matcher;

use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Mocking\DTO;

final readonly class Not implements Matcher
{
    public function __construct(
        private Matcher $matcher,
    ) {}

    public function matches(DTO $dto): bool
    {
        return ! $this->matcher->matches($dto);
    }
}
