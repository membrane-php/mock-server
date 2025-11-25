<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher\Matcher\Array;

use Membrane\MockServer\Matcher\Matcher\Array\Contains;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Contains
 */
#[UsesClass(Field::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Contains::class)]
final class ContainsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param mixed[] $values
     */
    #[Test]
    #[DataProvider('provideDTOsToCompare')]
    public function itChecksFieldsExist(
        bool $expected,
        Field $field,
        array $values,
        DTO $dto,
    ): void {
        self::assertSame(
            $expected,
            (new Contains($field, ...$values))
                ->matches($dto),
        );
    }

    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideArraysToConstructFrom')]
    public function itConstructsFromArray(
        Contains $expected,
        array $config,
    ): void {
        self::assertEquals($expected, Contains::fromArray($config));
    }

    /* @return \Generator<array{0: bool, 1: Field, 2: mixed[], 3: DTO}> */
    public static function provideDTOsToCompare(): \Generator
    {
        yield 'false if field missing' => [
            false,
            new Field('field', 'path'),
            ['Hello, world!'],
            new DTO([]),
        ];

        yield 'true if field contains value' => [
            true,
            new Field('field', 'path'),
            ['Hello, world!'],
            new DTO(['path' => ['field' => ['Hello, world!']]]),
        ];
        yield 'true if field contains values' => [
            true,
            new Field('field', 'path'),
            ['Hello, world!', 3.14],
            new DTO(['path' => ['field' => ['Hello, world!', 3.14]]]),
        ];
        yield 'true if field contains values and more' => [
            true,
            new Field('field', 'path'),
            ['Hello, world!', 3.14],
            new DTO(['path' => ['field' => ['Hello, world!', 3.14, 'more']]]),
        ];

        yield 'false if field contains some but not all values' => [
            false,
            new Field('field', 'path'),
            ['some', 'all'],
            new DTO(['path' => ['field' => ['some']]]),
        ];
    }

    /* @return \Generator<array{0: Contains, 1: Config}> */
    public static function provideArraysToConstructFrom(): \Generator
    {
        yield 'one value' => [
            new Contains(
                new Field('tags', 'query'),
                'cat',
            ),
            [
                'field' => ['query', 'tags'],
                'values' => ['cat'],
            ],
        ];

        yield 'three values' => [
            new Contains(
                new Field('tags', 'query'),
                'cat',
                'dog',
                'degu',
            ),
            [
                'field' => ['query', 'tags'],
                'values' => ['cat', 'dog', 'degu'],
            ],
        ];
    }
}
