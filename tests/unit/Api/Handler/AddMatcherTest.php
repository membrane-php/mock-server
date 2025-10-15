<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Handler\AddMatcher;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Response::class)]
#[UsesClass(Database\Model\Matcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Command\AddMatcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(AddMatcher::class)]
final class AddMatcherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param array<mixed> $args,
     * @param array<string, string|list<string>> $headers
     */
    #[Test]
    #[DataProvider('provideCommands')]
    public function itAddsMatcher(
        string $operationId,
        string $alias,
        array $args,
        int $responseCode,
        array $headers,
        string $body,
    ): void {
        $repository = new Fixture\Repository\Matcher();
        $idGenerator = new Fixture\IdGenerator();
        $id = $idGenerator->nextId();

        self::assertEquals(
            new Response(
                201,
                new Database\Model\Matcher(
                    $id,
                    $operationId,
                    $alias,
                    $args,
                    $responseCode,
                    $headers,
                    $body
                ),
            ),
            (new AddMatcher($repository, $idGenerator))(
                new Command\AddMatcher(
                    $operationId,
                    $alias,
                    $args,
                    $responseCode,
                    $headers,
                    $body,
                )),
        );

        self::assertNotNull($repository->fetchById($id));
    }

    /**
     * @return \Generator<array{
     *     0: string,
     *     1: string,
     *     2: array<mixed>,
     *     3: int,
     *     4: array<string, string|list<string>>,
     *     5: string,
     * }>
     */
    public static function provideCommands(): \Generator
    {
        yield 'mvp' => [
            'list-pets',
            'always-match',
            [],
            200,
            [],
            '',
        ];

        yield 'equals matcher' => [
            'findPetById',
            'equals',
            ['field' => 'id', 'value' => 5],
            200,
            ['Cache-control' => ['age=180']],
            '{"id":5}',
        ];
    }
}
