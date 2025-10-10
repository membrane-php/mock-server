<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\ConfigLocator;

use Membrane\MockServer\Mocking\ConfigLocator;
use Membrane\MockServer\Mocking\ConfigLocator\FromMultipleSources;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Mocking\Module
 */
#[\PHPUnit\Framework\Attributes\CoversClass(FromMultipleSources::class)]
final class FromMultipleSourcesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param OperationConfig $expected
     * @param ConfigLocator[] $configLocators
     */
    #[Test]
    #[DataProvider('provideConfigsToLocate')]
    public function itLocatesOperationConfigs(
        ?array $expected,
        array $configLocators,
        string $operationId,
    ): void {
        self::assertEqualsCanonicalizing($expected, (new ConfigLocator\FromMultipleSources(...$configLocators))
            ->getOperationConfig($operationId));
    }

    /**
     * @return \Generator<array{
     *     0: ?OperationConfig,
     *     1: ConfigLocator[],
     *     3: string,
     * }>
     */
    public static function provideConfigsToLocate(): \Generator
    {
        yield 'no config, returns null' => [
            null,
            [new Fixture\ConfigLocator([])],
            'find-pet-by-id',
        ];

        yield 'config in only source' => (function () {
            $expected = [
                'matchers' => [],
                'default' => ['response' => 200],
            ];

            return [
                $expected,
                [new Fixture\ConfigLocator(['listPets' => $expected])],
                'listPets',
            ];
        })();

        yield 'config in second source' => (function () {
            $unexpected = [
                'matchers' => [],
                'default' => ['response' => 404],
            ];
            $expected = [
                'matchers' => [],
                'default' => ['response' => 200],
            ];

            return [
                $expected,
                [
                    new Fixture\ConfigLocator(['listPets' => $unexpected]),
                    new Fixture\ConfigLocator(['find-pet-by-id' => $expected]),
                ],
                'find-pet-by-id',
            ];
        })();
    }
}
