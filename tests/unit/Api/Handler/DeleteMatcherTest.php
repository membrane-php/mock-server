<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Handler\DeleteMatcher;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Response::class)]
#[UsesClass(Database\Model\Matcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Command\DeleteMatcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(DeleteMatcher::class)]
final class DeleteMatcherTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideCommands')]
    public function itDeletesMatcher(
        string $id,
    ): void {
        $matcher = new Database\Model\Matcher($id, 'list-pets', 'equals', [], 400, [], '');

        $repository = new Fixture\Repository\Matcher();
        $repository->save($matcher);

        self::assertEquals(
            new Response(204),
            (new DeleteMatcher($repository))(
                new Command\DeleteMatcher($id)
            ),
        );

        self::assertNull($repository->fetchById($id));

    }

    /**
     * @return \Generator<array{
     *     0: string,
     *     1: string,
     * }>
     */
    public static function provideCommands(): \Generator
    {
        yield ['abc123'];

        yield ['def456'];
    }
}
