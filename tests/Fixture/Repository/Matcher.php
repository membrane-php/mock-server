<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture\Repository;

use Membrane\MockServer\Model;

final class Matcher implements \Membrane\MockServer\Database\Repository\Matcher
{
    /** @var array<string, array<string, \Membrane\MockServer\Database\Model\Matcher>> */
    private array $matchers;

    public function fetchByOperationId(string $operationId): array
    {
        return $this->matchers[$operationId] ?? [];
    }

    public function save(\Membrane\MockServer\Database\Model\Matcher $matcher): void
    {
        $this->matchers[$matcher->operationId][$matcher->id] = $matcher;
    }
}
