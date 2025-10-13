<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

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
