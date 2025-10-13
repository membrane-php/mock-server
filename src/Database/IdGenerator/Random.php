<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\IdGenerator;

use Membrane\MockServer\Database\IdGenerator;

final readonly class Random implements IdGenerator
{
    public function __construct(
        private ?\Random\Engine $engine = null,
    ) {}

    public function generateId(): string
    {
        return (new \Random\Randomizer($this->engine))
            ->getBytesFromString('abcdefghijklmnopqrstuvwxyz0123456789', 20);
    }
}
