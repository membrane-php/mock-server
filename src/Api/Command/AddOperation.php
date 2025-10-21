<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\WithNamedArguments;

#[SetFilterOrValidator(new WithNamedArguments(AddOperation::class), Placement::AFTER)]
final readonly class AddOperation
{
    /**
     * @param array<string, string|list<string>> $defaultResponseHeaders
     */
    public function __construct(
        public string $operationId,
        public int $defaultResponseCode,
        public array $defaultResponseHeaders,
        public string $defaultResponseBody,
    ) {}
}
