<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Handler\AddOperation;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Response::class)]
#[UsesClass(Database\Model\Operation::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Command\AddOperation::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(AddOperation::class)]
final class AddOperationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param array<string, string|list<string>> $headers
     */
    #[Test]
    #[DataProvider('provideCommands')]
    public function itAddsOperation(
        string $operationId,
        int $responseCode,
        array $headers,
        string $body,
    ): void {
        $repository = new Fixture\Repository\Operation();

        self::assertEquals(
            new Response(
                201,
                new Database\Model\Operation(
                    $operationId,
                    $responseCode,
                    $headers,
                    $body,
                ),
            ),
            (new AddOperation($repository))(
                new Command\AddOperation(
                    $operationId,
                    $responseCode,
                    $headers,
                    $body,
                )
            ),
        );

        self::assertNotNull($repository->fetchById($operationId));
    }

    /**
     * @return \Generator<array{
     *     0: string,
     *     1: int,
     *     2: array<string, string|list<string>>,
     *     3: string,
     * }>
     */
    public static function provideCommands(): \Generator
    {
        yield 'mvp' => [
            'findPetById',
            200,
            [],
            '',
        ];

        yield 'non-empty headers and body' => [
            'findPetById',
            200,
            ['Cache-control' => ['age=180']],
            '{"id":5}',
        ];
    }
}
