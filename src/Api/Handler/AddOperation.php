<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Database;

final readonly class AddOperation
{
    public function __construct(
        private Database\Repository\Operation $operationRepository,
    ) {}

    public function __invoke(Command\AddOperation $command): void
    {
        $operation = new Database\Model\Operation(
            $command->operationId,
            $command->defaultResponseCode,
            $command->defaultResponseHeaders,
            $command->defaultResponseBody,
        );

        $this->operationRepository->save($operation);
    }
}
