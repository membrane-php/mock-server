<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\WithNamedArguments;

#[SetFilterOrValidator(new WithNamedArguments(DeleteOperation::class), Placement::AFTER)]
final readonly class DeleteOperation
{
    public function __construct(
        public string $operationId,
    ) {}
}
