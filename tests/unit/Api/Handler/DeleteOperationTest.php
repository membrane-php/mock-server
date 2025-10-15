<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Handler\DeleteOperation;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Response::class)]
#[UsesClass(Database\Model\Operation::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Command\DeleteOperation::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(DeleteOperation::class)]
final class DeleteOperationTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideCommands')]
    public function itDeletesOperation(
        string $operationId,
    ): void {
        $operation = new Database\Model\Operation($operationId, 400, [], '');

        $repository = new Fixture\Repository\Operation();
        $repository->save($operation);

        self::assertEquals(
            new Response(204),
            (new DeleteOperation($repository))(
                new Command\DeleteOperation($operationId))
        );

        self::assertNull($repository->fetchById($operationId));

    }

    /**
     * @return \Generator<array{
     *     0: string,
     * }>
     */
    public static function provideCommands(): \Generator
    {
        yield ['findPetById'];

        yield ['list-pets'];
    }
}
