<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Matcher;

final readonly class AllOf implements Matcher
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
            if (!$matcher->matches($dto)) {
                return false;
            }
        }

        return true;
    }
}
