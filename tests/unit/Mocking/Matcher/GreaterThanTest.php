<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking\Matcher;

use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher\GreaterThan;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(Field::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(GreaterThan::class)]
final class GreaterThanTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideDTOsToCompare')]
    public function itChecksFieldIsLess(
        bool $expected,
        Field $field,
        float|int $limit,
        bool $inclusive,
        DTO $dto,
    ): void {
        self::assertSame($expected, (new GreaterThan(
            $field,
            $limit,
            $inclusive,
        ))->matches($dto));
    }

    /* @return \Generator<array{0: bool, 1: Field, 2: numeric, 3: DTO}> */
    public static function provideDTOsToCompare(): \Generator
    {
        yield 'false if field missing' => [
            false,
            new Field('my-string', 'path'),
            PHP_INT_MIN,
            true,
            new DTO([]),
        ];

        yield 'false if int outside limit' => [
            false,
            new Field('outside', 'path'),
            2,
            true,
            new DTO(['path' => ['outside' => 1]]),
        ];
        yield 'false if float outside limit' => [
            false,
            new Field('outside', 'path'),
            3.1415,
            true,
            new DTO(['path' => ['outside' => 3.14]]),
        ];

        yield 'false if int equals exclusive limit' => [
            false,
            new Field('equal', 'path'),
            2,
            false,
            new DTO(['path' => ['equal' => 2]]),
        ];
        yield 'false if float equals exclusive limit' => [
            false,
            new Field('equal', 'path'),
            3.141592,
            false,
            new DTO(['path' => ['equal' => 3.141592]]),
        ];

        yield 'true if int equals inclusive limit' => [
            true,
            new Field('equal', 'path'),
            2,
            true,
            new DTO(['path' => ['equal' => 2]]),
        ];
        yield 'true if float equals inclusive limit' => [
            true,
            new Field('equal', 'path'),
            3.141592,
            true,
            new DTO(['path' => ['equal' => 3.141592]]),
        ];
    }
}
