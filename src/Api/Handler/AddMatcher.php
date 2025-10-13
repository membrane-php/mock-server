<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Database;

final readonly class AddMatcher
{
    public function __construct(
        private Database\Repository\Matcher $matcherRepository,
        private Database\IdGenerator $idGenerator,
    ) {}

    public function __invoke(Command\AddMatcher $command): void
    {
        $matcher = new Database\Model\Matcher(
            $this->idGenerator->generateId(),
            $command->operationId,
            $command->alias,
            $command->args,
            $command->responseCode,
            $command->responseHeaders,
            $command->responseBody,
        );

        $this->matcherRepository->save($matcher);
    }
}
