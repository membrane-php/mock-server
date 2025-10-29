<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher\Matcher;

use Membrane\MockServer\Matcher\Matcher\Exists;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Exists
 */
#[UsesClass(Field::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Exists::class)]
final class ExistsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Field[] $fields
     */
    #[Test]
    #[DataProvider('provideDTOsToCompare')]
    public function itChecksFieldsExist(
        bool $expected,
        array $fields,
        DTO $dto,
    ): void {
        self::assertSame(
            $expected,
            (new Exists(...$fields))
                ->matches($dto),
        );
    }

    /**
     * @param Config[] $args
     */
    #[Test]
    #[DataProvider('provideArraysToConstructFrom')]
    public function itConstructsFromArray(
        Exists $expected,
        array $args,
    ): void {
        self::assertEquals($expected, Exists::fromArray($args));
    }

    /* @return \Generator<array{0: bool, 1: Field[], 2: DTO}> */
    public static function provideDTOsToCompare(): \Generator
    {
        yield 'false if no fields' => [
            false,
            [new Field('field', 'path')],
            new DTO([]),
        ];

        yield 'true if has field exactly' => [
            true,
            [new Field('field', 'path')],
            new DTO(['path' => ['field' => 123]]),
        ];

        yield 'true if has field and more' => [
            true,
            [new Field('field', 'path')],
            new DTO(['path' => ['field' => 123], 'query' => ['and' => 'more']]),
        ];

        yield 'true if has all fields' => [
            true,
            [new Field('all', 'path'), new Field('fields', 'path')],
            new DTO(['path' => ['all' => 123, 'fields' => 456]]),
        ];

        yield 'true if some but not all fields' => [
            false,
            [new Field('some', 'path'), new Field('not-all-fields', 'query')],
            new DTO(['path' => ['some' => 123]]),
        ];
    }

    /* @return \Generator<array{0: Exists, 1: Config}> */
    public static function provideArraysToConstructFrom(): \Generator
    {
        yield 'false if no fields' => [
            new Exists(new Field('id', 'path')),
            [['path', 'id']]
        ];
    }
}
