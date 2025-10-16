<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;

final readonly class DeleteOperation
{
    public function __construct(
        private Database\Repository\Operation $operationRepository,
    ) {}

    public function __invoke(Command\DeleteOperation $command): Response
    {
        $operation = $this->operationRepository->fetchById($command->operationId);
        if ($operation === null) {
            return new Response(400);
        }

        $this->operationRepository->remove($operation);
        return new Response(204);
    }
}
