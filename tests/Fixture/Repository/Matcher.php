<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture\Repository;

use Membrane\MockServer\Model;

final class Matcher implements \Membrane\MockServer\Repository\Matcher
{
    /** @var array<string, array<string, Model\Matcher>> */
    private array $matchers;

    public function fetchByOperationId(string $operationId): array
    {
        return $this->matchers[$operationId] ?? [];
    }

    public function save(Model\Matcher $matcher): void
    {
        $this->matchers[$matcher->operationId][$matcher->id] = $matcher;
    }
}
