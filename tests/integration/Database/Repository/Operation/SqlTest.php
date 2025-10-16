<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Integration\Database\Repository\Operation;

use Membrane\MockServer\Database\Model;
use Membrane\MockServer\Database\Repository\Operation;
use Membrane\MockServer\Database\Schema\MatcherTable;
use Membrane\MockServer\Database\Schema\OperationTable;
use Membrane\MockServer\Tests\Fixture;
use Membrane\MockServer\Tests\Integration\UsesDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(MatcherTable::class)]
#[UsesClass(Model\Operation::class)]
/**
 * 1. OperationTable MUST migrate successfully for the Sql repository to work.
 * 2. OperationTable has no behaviour worth testing in isolation
 */
#[\PHPUnit\Framework\Attributes\CoversClass(OperationTable::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Operation\Sql::class)]
final class SqlTest extends \PHPUnit\Framework\TestCase
{
    use UsesDatabase;

    #[Test]
    public function itRemovesOperations(): void
    {
        $this->getMigrator()->drop();
        $this->getMigrator()->migrate();

        $operation = Fixture\ProvidesOperations::generate()->current();
        $sut = $this->getOperationRepository();

        $sut->save($operation);
        self::assertNotNull($sut->fetchById($operation->operationId));

        $sut->remove($operation);
        self::assertNull($sut->fetchById($operation->operationId));
    }

    /**
     * @param Model\Operation[] $operations
     */
    #[Test]
    #[DataProvider('provideOperationsToFetch')]
    public function itFetchesByOperationId(
        ?Model\Operation $expected,
        array $operations,
        string $id,
    ): void {
        $this->getMigrator()->drop();
        $this->getMigrator()->migrate();

        $sut = $this->getOperationRepository();

        foreach ($operations as $operation) {
            $sut->save($operation);
        }

        self::assertEquals($expected, $sut->fetchById($id));
    }

    /**
     * @return \Generator<array{
     *     0: ?Model\Operation,
     *     1: Model\Operation[],
     *     2: string,
     *  }>
     */
    public static function provideOperationsToFetch(): \Generator
    {
        yield 'no operations' => [null, [], 'list-pets'];
        yield 'non-matching operations' => [
            null,
            iterator_to_array(Fixture\ProvidesOperations::generate()),
            'howdy-planet',
        ];
        yield 'matching operation' => (function () {
            $operations = Fixture\ProvidesOperations::generate();

            $expected = $operations->current();

            return [
                $expected,
                iterator_to_array($operations),
                $expected->operationId,
            ];
        })();
    }
}
