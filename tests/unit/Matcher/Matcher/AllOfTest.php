<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher\Matcher;

use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Matcher\Matcher\AllOf;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(DTO::class)]
#[UsesClass(Fixture\Matcher::class)]
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
        yield 'one match' => [true, new Fixture\Matcher(matches: true)];
        yield 'one non-match' => [false, new Fixture\Matcher(matches: false)];

        yield 'two matches' => [
            true,
            new Fixture\Matcher(matches: true),
            new Fixture\Matcher(matches: true),
        ];
        yield 'two non-matches' => [
            false,
            new Fixture\Matcher(matches: false),
            new Fixture\Matcher(matches: false),
        ];

        yield 'one match, one non-match' => [
            false,
            new Fixture\Matcher(matches: true),
            new Fixture\Matcher(matches: false),
        ];
        yield 'several matches & non-matches' => [
            false,
            new Fixture\Matcher(matches: false),
            new Fixture\Matcher(matches: true),
            new Fixture\Matcher(matches: false),
            new Fixture\Matcher(matches: true),
        ];
    }
}
