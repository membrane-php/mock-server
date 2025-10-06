<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit;

use Membrane\MockServer\ConfigLocator;
use Membrane\MockServer\DTO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-import-type OperationMap from ConfigLocator
 * @phpstan-import-type OperationConfig from ConfigLocator
 */
#[UsesClass(DTO::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(ConfigLocator::class)]
final class ConfigLocatorTest extends \PHPUnit\Framework\TestCase
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
        self::assertSame($expected, (new ConfigLocator($operationMap))
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
