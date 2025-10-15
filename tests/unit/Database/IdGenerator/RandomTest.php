<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Database\IdGenerator;


use Membrane\MockServer\Database\IdGenerator\Random;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\CoversClass(Random::class)]
final class RandomTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function itGeneratesId(): void
    {
        $notSoRandomEngine = new class implements \Random\Engine
        {
            public function generate(): string
            {
                return 'Hello, world!';
            }
        };

        $sut = new Random($notSoRandomEngine);

        self::assertEquals('i6i6i6i6i6i6i6i6i6i6', $sut->generateId());
    }

    #[Test]
    public function itGeneratesUniqueIds(): void
    {
        $sut = new Random();

        self::assertNotSame($sut->generateId(), $sut->generateId());
    }
}
