<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher\Matcher;

use Membrane\MockServer\Matcher\Matcher\Equals;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Field::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Equals::class)]
final class EqualsTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideDTOsToCompare')]
    public function itChecksFieldIsEqual(
        bool $expected,
        Field $field,
        mixed $value,
        DTO $dto,
    ): void {
        self::assertSame(
            $expected,
            (new Equals($field, $value))
                ->matches($dto),
        );
    }

    /* @return \Generator<array{0: bool, 1: Field, 2: mixed, 3: DTO}> */
    public static function provideDTOsToCompare(): \Generator
    {
        yield 'matching null in path' => [
            true,
            new Field('null-field', 'path'),
            null,
            new DTO(['path' => ['null-field' => null]]),
        ];
        yield 'matching string in query' => [
            true,
            new Field('name', 'query'),
            'sprig',
            new DTO(['query' => ['name' => 'sprig']]),
        ];
        yield 'matching bool in cookie' => [
            true,
            new Field('ExampleEnabled', 'cookie'),
            false,
            new DTO(['cookie' => ['ExampleEnabled' => false]]),
        ];
        yield 'matching float in header' => [
            true,
            new Field('pi', 'header'),
            3.141592,
            new DTO(['header' => ['pi' => 3.141592]]),
        ];
        yield 'matching nested field in body' => [
            true,
            new Field('name', 'body', 'character'),
            'sprig',
            new DTO(['body' => ['character' => ['name' => 'sprig']]]),
        ];


        yield 'non-matching string in header' => [
            false,
            new Field('greeting', 'header'),
            'Hello, World!',
            new DTO(['header' => ['greeting' => 'Howdy, Planet!']]),
        ];
        yield 'non-matching nested field in body' => [
            false,
            new Field('name', 'body', 'character'),
            'sprig',
            new DTO(['body' => ['character' => ['name' => 'polly']]]),
        ];
    }
}
