<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\Matcher;

use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Matcher;

final readonly class AnyOf implements Matcher
{
    /** @var non-empty-array<Matcher> */
    private array $matchers;

    public function __construct(Matcher $matcher, Matcher ...$matchers)
    {
        $this->matchers = [$matcher, ...$matchers];
    }

    public function matches(DTO $dto): bool
    {
        foreach ($this->matchers as $matcher) {
            if ($matcher->matches($dto)) {
                return true;
            }
        }

        return false;
    }
}
