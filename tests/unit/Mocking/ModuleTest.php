<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking;

use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\CoversClass(Field::class)]
final class ModuleTest extends \PHPUnit\Framework\TestCase
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
     * @param non-empty-list<string> $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itConstructsFromConfig(
        Field $expected,
        array $config,
    ): void {
        self::assertEquals($expected, Field::fromConfig($config));
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

    /**
     * @return \Generator<array{
     *     0: Field,
     *     1: non-empty-list<string>
     *  }>
     */
    public static function provideConfigs(): \Generator
    {
        yield 'id' => [new Field('id'), ['id']];
        yield 'path->id' => [new Field('id', 'path'), ['path', 'id']];
        yield 'path->pet->id' => [new Field('id', 'path', 'pet'), ['path', 'pet', 'id']];
    }
}
