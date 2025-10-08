<?php

declare(strict_types=1);

namespace Membrane\MockServer\Repository;

use Membrane\MockServer\Model;

interface Operation
{
    public function fetchById(string $id): ?Model\Operation;

    public function save(Model\Operation $operation): void;
}
