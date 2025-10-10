<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking\Matcher;

use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Matcher;
use Membrane\MockServer\Mocking\Matcher\Not;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(DTO::class)]
#[UsesClass(Fixture\Matcher::class)]
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
        yield 'not: always-match' => [new Fixture\Matcher(matches: true)];
        yield 'not: never-match' => [new Fixture\Matcher(matches: false)];
    }
}
