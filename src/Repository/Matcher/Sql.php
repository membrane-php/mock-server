<?php

declare(strict_types=1);

namespace Membrane\MockServer\Repository\Matcher;

use Membrane\MockServer\Generated\Repository\MockServer\Model\SQLite;

/**
 * @TODO this cannot handle shorthand `int` responses, they must be arrays
 * @TODO this cannot handle shorthand `string` response bodies, they must be arrays
 */
final class Sql extends Sqlite\MatcherRepository implements \Membrane\MockServer\Repository\Matcher
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
