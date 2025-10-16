<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture\Repository;

use Membrane\MockServer\Database\Model;

final class Matcher implements \Membrane\MockServer\Database\Repository\Matcher
{
    /** @var array<string, array<string, Model\Matcher>> */
    private array $matchers = [];

    public function fetchById(string $id): ?Model\Matcher
    {
        foreach ($this->matchers as $operationMatchers) {
            foreach ($operationMatchers as $matcher) {
                if ($matcher->id === $id) {
                    return $matcher;
                }
            }
        }

        return null;
    }

    public function fetchByOperationId(string $operationId): array
    {
        return $this->matchers[$operationId] ?? [];
    }

    public function save(Model\Matcher $matcher): void
    {
        $this->matchers[$matcher->operationId][$matcher->id] = $matcher;
    }

    public function remove(Model\Matcher $matcher): void
    {
        unset($this->matchers[$matcher->operationId][$matcher->id]);
    }

}
