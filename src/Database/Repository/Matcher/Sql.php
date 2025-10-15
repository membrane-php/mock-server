<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Repository\Matcher;

use Membrane\MockServer\Database\Repository;
use Membrane\MockServer\Generated\Repository\MockServer\Database\Model\SQLite;

final class Sql extends Sqlite\MatcherRepository implements Repository\Matcher
{
    public function fetchByOperationId(string $operationId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $query = $qb->select('*')
            ->from(self::TABLE_NAME)
            ->where('operationId = :operationId')
            ->setParameter('operationId', $operationId);

        return $this->hydrateArray($query);
    }
}
