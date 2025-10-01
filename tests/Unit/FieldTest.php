<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\CoversClass(Field::class)]
final class FieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param list<string> $path
     */
    #[Test]
    #[DataProvider('provideFieldsToFind')]
    public function itFindsFields(
        mixed $expected,
        string $name,
        array $path,
        DTO $dto,
    ): void {
        self::assertEquals($expected, (new Field($name, ...$path))->find($dto));
    }

    /**
     * @return \Generator<array{
     *     0: mixed,
     *     1: string,
     *     2: list<string>,
     *     3: array<string, mixed>
     * }>
     */
    public static function provideFieldsToFind(): \Generator
    {
        yield 'no data' => [null, 'example', [], new DTO([])];

        yield 'field exists' => [
            'Hello, World!',
            'example',
            ['path'],
            new DTO([
                'path' => [
                    'example' => 'Hello, World!',
                    'not-example' => 'Howdy, planet!',
                ],
                'not-path' => [
                    'example' => 'Good day, globe.',
                ],
                'example' => 'Greetings, Gaia!',
            ]),
        ];

        yield 'field does not exist' => [
            null,
            'example',
            ['path'],
            new DTO([
                'path' => [
                    'not-example' => 'Howdy, planet!',
                ],
                'not-path' => [
                    'example' => 'Good day, globe.',
                ],
                'example' => 'Greetings, Gaia!',
            ]),
        ];
    }
}
