<?php

declare(strict_types=1);

namespace Membrane\MockServer\Repository;

use Membrane\MockServer\Generated\Repository\MockServer\Model\SQLite;
use Membrane\MockServer\Model\Matcher;

final class MatcherRepository extends Sqlite\MatcherRepository
{
    /** @return Matcher[] */
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
