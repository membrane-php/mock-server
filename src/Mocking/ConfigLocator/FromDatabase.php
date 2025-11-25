<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\ConfigLocator;

use Membrane\MockServer\Database\Repository;
use Membrane\MockServer\Mocking\ConfigLocator;

/**
 * @phpstan-import-type Config from ConfigLocator
 */
final readonly class FromDatabase implements ConfigLocator
{
    public function __construct(
        private Repository\Operation $operationRepository,
        private Repository\Matcher $matcherRepository,
    ) {}

    /** @return ?Config */
    public function getOperationConfig(string $operationId): ?array
    {
        $operation = $this->operationRepository->fetchById($operationId) ?? null;

        if ($operation === null) {
            return null;
        }

        $matchers = $this->matcherRepository->fetchByOperationId($operationId);

        return array_merge(
            $operation->jsonSerialize(),
            ['matchers' => array_values(array_map(
                fn($m) => $m->jsonSerialize(),
                $matchers,
            ))],
        );
    }
}
