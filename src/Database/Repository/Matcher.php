<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Repository;

use Membrane\MockServer\Database\Model;

interface Matcher
{
    /** @return Model\Matcher[] */
    public function fetchByOperationId(string $operationId): array;

    public function save(Model\Matcher $matcher): void;

    public function remove(Model\Matcher $matcher): void;
}
