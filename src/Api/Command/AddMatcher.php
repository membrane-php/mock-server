<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

final readonly class AddMatcher
{
    /**
     * @param array<mixed> $args
     * @param array<string, string|list<string>> $responseHeaders
     */
    public function __construct(
        public string $operationId,
        public string $matcherId,
        public string $alias,
        public array $args,
        public int $responseCode,
        public array $responseHeaders,
        public string $responseBody,
    ) {}
}
