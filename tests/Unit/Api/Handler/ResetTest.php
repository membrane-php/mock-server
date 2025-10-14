<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api\Handler;

use Atto\Db\Migrator;
use Atto\Db\TableSchema;
use bovigo\vfs\vfsStream;
use Doctrine\DBAL\Connection;
use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Handler\Reset;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Response::class)]
#[UsesClass(Command\Reset::class)]
#[UsesClass(Database\Model\Matcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Reset::class)]
final class ResetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param array<class-string<TableSchema>> $migrations,
     */
    #[Test]
    #[DataProvider('provideMigrations')]
    public function itResetsEverything(
        array $migrations,
    ): void {
        $dbContent = 'SQLite format 3';
        vfsStream::setup(structure: [
            'storage' => [
                'db_sqlite' => $dbContent,
                'backup' => $dbContent,
            ]
        ]);
        $storagePath = vfsStream::url('root/storage/db_sqlite');

        $migrator = new Migrator(
            self::createMock(Connection::class),
            [],
        );

        self::assertEquals(
            new Response(204),
            (new Reset($storagePath, $migrator))(new Command\Reset()),
        );

        if (empty($migrations)) {
            self::assertFileDoesNotExist($storagePath);
        } else {
            self::assertFileNotEquals(
                vfsStream::url('root/storage/backup'),
                $storagePath
            );
        }

    }

    /**
     * @return \Generator<array{
     *     0: array<class-string<TableSchema>>
     * }>
     */
    public static function provideMigrations(): \Generator
    {
        yield 'no migrations' => [[]];
    }
}
