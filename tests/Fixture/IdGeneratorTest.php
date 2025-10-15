<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;

#[\PHPUnit\Framework\Attributes\CoversClass(IdGenerator::class)]
final class IdGeneratorTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function itGetsNextId(): void
    {
        $sut = new IdGenerator();
        self::assertSame($sut->nextId(), $sut->generateId());
    }

    #[Test]
    #[TestDox('It generates enough unique IDs for testing purposes')]
    public function itGeneratesUniqueIds(): void
    {
        $sut = new IdGenerator();

        $ids = [];
        while (count($ids) < 20) {
            $ids[] = $sut->generateId();
        }

        self::assertEquals(array_unique($ids), $ids);
    }
}
