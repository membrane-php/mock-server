<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking\Matcher\String;

use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher\String\Regex;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(DTO::class)]
#[UsesClass(Field::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Regex::class)]
final class RegexTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideDTOsToCompare')]
    public function itChecksFieldMatches(
        bool $expected,
        Field $field,
        string $regex,
        DTO $dto,
    ): void {
        self::assertSame($expected, (new Regex(
            $field,
            $regex,
        ))->matches($dto));
    }

    /* @return \Generator<array{0: bool, 1: Field, 2: mixed, 3: DTO}> */
    public static function provideDTOsToCompare(): \Generator
    {
        yield 'false if field missing' => [
            false,
            new Field('my-string', 'path'),
            '#.*#',
            new DTO([]),
        ];
        yield 'false if field not string' => [
            false,
            new Field('my-number', 'path'),
            '#.*#',
            new DTO(['path' => ['my-number' => 3.141592]]),
        ];


        yield 'true if field matches .*' => [
            true,
            new Field('my-string', 'path'),
            '#.*#',
            new DTO(['path' => ['my-string' => 'Hello, world!']]),
        ];
        yield 'true if field matches ^\d\.\d{6}$' => [
            true,
            new Field('pi', 'cookie'),
            '#^\d\.\d{6}$#',
            new DTO(['cookie' => ['pi' => '3.141592']]),
        ];

        yield 'false if field differs from ^\d\.\d{6}$' => [
            false,
            new Field('pi', 'cookie'),
            '#^\d\.\d{6}$#',
            new DTO(['cookie' => ['pi' => '3.14']]),
        ];
    }
}
