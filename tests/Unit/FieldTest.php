<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit;

use Membrane\MockServer\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\CoversClass(Field::class)]
final class FieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param list<string> $path
     * @param array<string, mixed> $data
     */
    #[Test]
    #[DataProvider('provideFieldsToFind')]
    public function itFindsFields(
        mixed $expected,
        string $name,
        array $path,
        array $data,
    ): void {
        self::assertEquals($expected, (new Field($name, ...$path))->find($data));
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
        yield 'no data' => [null, 'example', [], []];

        yield 'field exists' => [
            'Hello, World!',
            'example',
            ['path'],
            [
                'path' => [
                    'example' => 'Hello, World!',
                    'not-example' => 'Howdy, planet!',
                ],
                'not-path' => [
                    'example' => 'Good day, globe.',
                ],
                'example' => 'Greetings, Gaia!'
            ],
        ];

        yield 'field does not exist' => [
            null,
            'example',
            ['path'],
            [
                'path' => [
                    'not-example' => 'Howdy, planet!',
                ],
                'not-path' => [
                    'example' => 'Good day, globe.',
                ],
                'example' => 'Greetings, Gaia!'
            ],
        ];
    }
}
