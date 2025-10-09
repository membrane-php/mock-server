<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Repository;

use Membrane\MockServer\Model;

interface Operation
{
    public function fetchById(string $id): ?\Membrane\MockServer\Database\Model\Operation;

    public function save(\Membrane\MockServer\Database\Model\Operation $operation): void;
}
