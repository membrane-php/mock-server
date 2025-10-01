<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;
use Membrane\MockServer\Matcher\Exists;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

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
}
