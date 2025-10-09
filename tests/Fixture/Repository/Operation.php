<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture\Repository;

use Membrane\MockServer\Model;

final class Operation implements \Membrane\MockServer\Database\Repository\Operation
{
    /** @var array<string, \Membrane\MockServer\Database\Model\Operation> */
    private array $operations;

    public function fetchById(string $id): ?\Membrane\MockServer\Database\Model\Operation
    {
        return $this->operations[$id] ?? null;
    }

    public function save(\Membrane\MockServer\Database\Model\Operation $operation): void
    {
        $this->operations[$operation->operationId] = $operation;
    }
}
