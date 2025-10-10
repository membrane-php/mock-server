<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\ConfigLocator;

use Membrane\MockServer\Database\Repository;

final readonly class FromDatabase implements \Membrane\MockServer\Mocking\ConfigLocator
{
    public function __construct(
        private Repository\Operation $operationRepository,
        private Repository\Matcher $matcherRepository,
    ) {}

    public function getOperationConfig(string $operationId): ?array
    {
        $operation = $this->operationRepository->fetchById($operationId) ?? null;

        if ($operation === null) {
            return null;
        }

        $matchers = $this->matcherRepository->fetchByOperationId($operationId);

        return array_merge(
            $operation->jsonSerialize(),
            ['matchers' => array_map(fn($m) => $m->jsonSerialize(), $matchers)],
        );
    }
}
