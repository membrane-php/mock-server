<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

final readonly class DeleteOperation
{
    public function __construct(
        public string $operationId,
    ) {}
}
