<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Handler\DeleteOperation;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Response::class)]
#[UsesClass(Database\Model\Operation::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Command\DeleteOperation::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(DeleteOperation::class)]
final class DeleteOperationTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function itFailsIfOperationDoesNotExist(): void
    {
        self::assertEquals(
            new Response(400),
            (new DeleteOperation(new Fixture\Repository\Operation()))(
                new Command\DeleteOperation('abc123')
            ),
        );
    }

    #[Test]
    public function itDeletesOperation(): void
    {
        $operation = Fixture\ProvidesOperations::generate()->current();

        $repository = new Fixture\Repository\Operation();
        $repository->save($operation);

        self::assertEquals(
            new Response(204),
            (new DeleteOperation($repository))(
                new Command\DeleteOperation($operation->operationId)
            ),
        );

        self::assertNull($repository->fetchById($operation->operationId));
    }
}
