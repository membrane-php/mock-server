<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Database;

final readonly class DeleteMatcher
{
    public function __construct(
        private Database\Repository\Matcher $matcherRepository,
    ) {}

    public function __invoke(Command\DeleteMatcher $command): void
    {
        $matchers = $this->matcherRepository
            ->fetchByOperationId($command->operationId);

        foreach ($matchers as $matcher) {
            if ($matcher->id === $command->matcherId) {
                $this->matcherRepository->remove($matcher);
                return;
            }
        }
    }
}
