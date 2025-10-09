<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking;

interface Matcher
{
    public function matches(DTO $dto): bool;
}
