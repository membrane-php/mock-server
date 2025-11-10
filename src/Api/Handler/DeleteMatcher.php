<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;

final readonly class DeleteMatcher
{
    public function __construct(
        private Database\Repository\Matcher $matcherRepository,
    ) {}

    public function __invoke(Command\DeleteMatcher $command): Response
    {
        $matcher = $this->matcherRepository->fetchById($command->matcherId);
        if (
            $matcher === null
            || $matcher->operationId !== $command->operationId
        ) {
            return new Response(400);
        }

        $this->matcherRepository->remove($matcher);
        return new Response(204);
    }
}
