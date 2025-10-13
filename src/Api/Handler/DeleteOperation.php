<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Database;

final readonly class DeleteOperation
{
    public function __construct(
        private Database\Repository\Operation $operationRepository,
    ) {}

    public function __invoke(Command\DeleteOperation $command): void
    {
        $operation = $this->operationRepository
            ->fetchById($command->operationId);

        if ($operation !== null) {
            $this->operationRepository
                ->remove($operation);
        }
    }
}
