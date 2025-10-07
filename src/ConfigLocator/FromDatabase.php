<?php

declare(strict_types=1);

namespace Membrane\MockServer\ConfigLocator;

use Membrane\MockServer\ConfigLocator;
use Membrane\MockServer\Generated\Repository\MockServer\Model\SQLite\MatcherRepository;
use Membrane\MockServer\Generated\Repository\MockServer\Model\SQLite\OperationRepository;

final readonly class FromDatabase implements ConfigLocator
{
    public function __construct(
        OperationRepository $operationRepository,
        MatcherRepository $matcherRepository,
    ) {}

    public function getOperationConfig(string $operationId): ?array
    {

    }
}
