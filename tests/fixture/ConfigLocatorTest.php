<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Mocking\Module
 */
#[\PHPUnit\Framework\Attributes\CoversClass(ConfigLocator::class)]
final class ConfigLocatorTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @param ?OperationConfig $expected
     * @param array<string, OperationConfig> $config
     */
    #[Test]
    #[DataProvider('provideConfig')]
    public function itReturnsConfig(
        ?array $expected,
        array $config,
        string $operationId,
    ): void {
        self::assertSame($expected, (new ConfigLocator($config))
            ->getOperationConfig($operationId));
    }

    /**
     * @return \Generator<array{
     *     0: OperationConfig,
     *     1: array<string, OperationConfig>,
     *     2: string,
     *  }>
     */
    public static function provideConfig(): \Generator
    {
        yield 'no config' => [null, [], 'list-pets'];

        yield 'no matching config' => [
            null,
            ['findPetById' => ['default' => ['response' => 200]]],
            'list-pets',
        ];

        yield 'matching config' => [
            ['default' => ['response' => 204]],
            [
                'findPetById' => ['default' => ['response' => 200]],
                'delete-pet' => ['default' => ['response' => 204]],
            ],
            'delete-pet',
        ];
    }
}
