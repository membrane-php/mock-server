<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Integration;

use Atto\Db;
use Doctrine\DBAL\Connection;
use Membrane\MockServer\Database;

trait UsesDatabase
{
    private const string DB_PATH = ':memory:';
    private ?Connection $connection = null;
    private ?Db\Migrator $migrator = null;

    private function getConnection(): Connection
    {
        if (!isset($this->connection)) {
            $this->connection = (new Db\DbFactory())([
                'driver' => 'pdo_sqlite',
                'path' => self::DB_PATH,
            ]);
        }

        return $this->connection;
    }

    private function getMigrator(): Db\Migrator
    {
        if (!isset($this->migrator)) {
            $this->migrator = new Db\Migrator($this->getConnection(), [
                Database\Schema\OperationTable::class,
                Database\Schema\MatcherTable::class,
            ]);
        }

        return $this->migrator;
    }

    private function getOperationRepository(): Database\Repository\Operation
    {
        return new Database\Repository\Operation\Sql($this->getConnection());
    }

    private function getMatcherRepository(): Database\Repository\Matcher
    {
        return new Database\Repository\Matcher\Sql($this->getConnection());
    }
}
