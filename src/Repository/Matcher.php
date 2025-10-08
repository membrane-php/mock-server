<?php

declare(strict_types=1);

namespace Membrane\MockServer\Repository;

use Membrane\MockServer\Model;

interface Matcher
{
    /** @return Model\Matcher[] */
    public function fetchByOperationId(string $operationId): array;

    public function save(Model\Matcher $matcher): void;
}
