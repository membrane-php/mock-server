<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Matcher;
use Membrane\MockServer\Matcher\AllOf;
use Membrane\MockServer\Tests\Fixture\Matcher\AlwaysMatch;
use Membrane\MockServer\Tests\Fixture\Matcher\NeverMatch;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(NeverMatch::class)]
#[UsesClass(AlwaysMatch::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(AllOf::class)]
final class AllOfTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideDTOsToCompare')]
    public function itChecksEverythingMatches(
        bool $expected,
        Matcher ...$matchers,
    ): void {
        self::assertSame(
            $expected,
            (new AllOf(...$matchers))
                ->matches(new DTO([])),
        );
    }

    /* @return \Generator<array{0: bool, ...Matcher}> */
    public static function provideDTOsToCompare(): \Generator
    {
        yield 'one match' => [true, new AlwaysMatch()];
        yield 'one non-match' => [false, new NeverMatch()];

        yield 'two matches' => [true, new AlwaysMatch(), new AlwaysMatch()];
        yield 'two non-matches' => [false, new NeverMatch(), new NeverMatch()];

        yield 'one match, one non-match' => [
            false,
            new AlwaysMatch(),
            new NeverMatch(),
        ];
        yield 'several matches & non-matches' => [
            false,
            new NeverMatch(),
            new AlwaysMatch(),
            new NeverMatch(),
            new AlwaysMatch(),
        ];
    }
}
