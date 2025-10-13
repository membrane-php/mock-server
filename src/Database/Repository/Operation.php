<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Repository;

use Membrane\MockServer\Database\Model;

interface Operation
{
    public function fetchById(string $id): ?Model\Operation;

    public function save(Model\Operation $operation): void;

    public function remove(Model\Operation $operation): void;
}
