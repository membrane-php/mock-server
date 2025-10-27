<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\Mocking\DTO;

interface Matcher
{
    public function matches(DTO $dto): bool;
}
