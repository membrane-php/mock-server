<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\ConfigLocator;

use Membrane\MockServer\Mocking\ConfigLocator\FromApplicationConfig;
use Membrane\MockServer\Mocking\DTO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type OperationMap from FromApplicationConfig
 * @phpstan-import-type OperationConfig from FromApplicationConfig
 */
#[UsesClass(DTO::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(FromApplicationConfig::class)]
final class FromApplicationConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param OperationConfig $expected
     * @param OperationMap $operationMap
     */
    #[Test]
    #[DataProvider('provideConfigsToLocate')]
    public function itLocatesOperationConfigs(
        ?array $expected,
        array $operationMap,
        string $operationId,
    ): void {
        self::assertSame($expected, (new FromApplicationConfig($operationMap))
            ->getOperationConfig($operationId));
    }

    /**
     * @return \Generator<array{
     *     0: ?OperationConfig,
     *     1: OperationMap,
     *     2: string,
     * }>
     */
    public static function provideConfigsToLocate(): \Generator
    {
        yield 'no config, returns null' => [null, [], 'find-pet-by-id'];

        yield 'default int response' => [
            ['default' => ['response' => 200]],
            ['findPets' => ['default' => ['response' => 200]]],
            'findPets',
        ];

        yield 'default array response; body as string' => [
            [
                'default' => ['response' => [
                    'code' => 201,
                    'headers' => ['is-test' => true],
                    'body' => 'Hello, World!',
                ]],
            ],
            ['delete-pet' => [
                'default' => ['response' => [
                    'code' => 201,
                    'headers' => ['is-test' => true],
                    'body' => 'Hello, World!',
                ]],
            ]],
            'delete-pet',
        ];
    }
}
