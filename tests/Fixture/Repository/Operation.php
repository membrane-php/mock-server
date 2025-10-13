<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture\Repository;

use Membrane\MockServer\Database\Model;

final class Operation implements \Membrane\MockServer\Database\Repository\Operation
{
    /** @var array<string, Model\Operation> */
    private array $operations;

    public function fetchById(string $id): ?Model\Operation
    {
        return $this->operations[$id] ?? null;
    }

    public function save(Model\Operation $operation): void
    {
        $this->operations[$operation->operationId] = $operation;
    }

    public function remove(Model\Operation $operation): void
    {
        unset($this->operations[$operation->operationId]);
    }

}
