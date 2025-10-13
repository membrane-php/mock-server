<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

final readonly class DeleteMatcher
{
    public function __construct(
        public string $operationId,
        public string $matcherId,
    ) {}
}
