<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\WithNamedArguments;

#[SetFilterOrValidator(new WithNamedArguments(DeleteMatcher::class), Placement::AFTER)]
final readonly class DeleteMatcher
{
    public function __construct(
        public string $matcherId,
        public string $operationId,
    ) {}
}
