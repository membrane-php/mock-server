<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Handler\DeleteMatcher;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Response::class)]
#[UsesClass(Database\Model\Matcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Command\DeleteMatcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(DeleteMatcher::class)]
final class DeleteMatcherTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function itFailsIfMatcherDoesNotExist(): void
    {
        self::assertEquals(
            new Response(404),
            (new DeleteMatcher(new Fixture\Repository\Matcher()))(
                new Command\DeleteMatcher('showPets', 'abc123'),
            ),
        );
    }

    #[Test]
    public function itDeletesMatcher(): void
    {
        $matcher = Fixture\ProvidesMatchers::generate()->current();

        $repository = new Fixture\Repository\Matcher();
        $repository->save($matcher);

        self::assertEquals(
            new Response(204),
            (new DeleteMatcher($repository))(
                new Command\DeleteMatcher($matcher->id, $matcher->operationId),
            ),
        );

        self::assertNull($repository->fetchById($matcher->id));
    }
}
