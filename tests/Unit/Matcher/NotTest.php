<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Matcher;
use Membrane\MockServer\Matcher\Not;
use Membrane\MockServer\Tests\Fixture\Matcher\AlwaysMatch;
use Membrane\MockServer\Tests\Fixture\Matcher\NeverMatch;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(NeverMatch::class)]
#[UsesClass(AlwaysMatch::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Not::class)]
final class NotTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideDTOsToCompare')]
    public function itChecksNotMatching(
        Matcher $matcher,
    ): void {
        $dto = new DTO([]);
        self::assertSame(
            ! $matcher->matches($dto),
            (new Not($matcher))->matches($dto),
        );
    }

    /* @return \Generator<array{0: bool, ...Matcher}> */
    public static function provideDTOsToCompare(): \Generator
    {
        yield 'not: always-match' => [new AlwaysMatch()];
        yield 'not: never-match' => [new NeverMatch()];
    }
}
