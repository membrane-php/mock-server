<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture\Matcher;

final readonly class NeverMatch implements \Membrane\MockServer\Matcher
{
    public function matches(\Membrane\MockServer\DTO $dto): false
    {
        return false;
    }
}
