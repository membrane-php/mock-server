<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture;

final class IdGenerator implements \Membrane\MockServer\Database\IdGenerator
{
    private int $increment = 0;

    public function generateId(): string
    {
        $result = $this->nextId();
        $this->increment += 1;
        return $result;
    }

    public function nextId(): string {
        return sprintf('id%s', $this->increment);
    }
}
