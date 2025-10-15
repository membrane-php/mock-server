<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking;

use Membrane\MockServer\Mocking\DTO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * @phpstan-import-type DTOArray from DTO
 */
#[\PHPUnit\Framework\Attributes\CoversClass(DTO::class)]
final class DTOTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param DTOArray $data
     */
    #[Test]
    #[DataProvider('provideArraysOfData')]
    public function itConstructsIdenticallyFromArray(
        array $data,
    ): void {
        self::assertEquals(new DTO($data), DTO::fromArray($data));
    }

    /**
     * @return \Generator<array{
     *     0: DTOArray,
     * }>
     */
    public static function provideArraysOfData(): \Generator
    {
        yield 'no data' => [[]];
        yield 'field exists' => [[
            'request' => ['operationId' => 'list-pets'],
            'path' => [
                'example' => 'Hello, World!',
                'not-example' => 'Howdy, planet!',
            ],
        ]];
    }
}
