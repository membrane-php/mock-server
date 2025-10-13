<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database;

interface IdGenerator
{
    public function generateId(): string;
}
