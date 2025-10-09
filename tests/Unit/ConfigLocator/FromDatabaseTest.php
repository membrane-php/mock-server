<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\ConfigLocator;

use Membrane\MockServer\Tests\Fixture;
use Membrane\MockServer\ConfigLocator\FromDatabase;
use Membrane\MockServer\Model;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Module
 */
#[UsesClass(Model\Matcher::class)]
#[UsesClass(Model\Operation::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(FromDatabase::class)]
final class FromDatabaseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param OperationConfig $expected
     * @param Model\Matcher[] $matchers
     */
    #[Test]
    #[DataProvider('provideConfigsToLocate')]
    public function itLocatesOperationConfigs(
        ?array $expected,
        ?Model\Operation $operation,
        array $matchers,
        string $operationId,
    ): void {
        $operationRepository = new Fixture\Repository\Operation();
        if ($operation !== null) {
            $operationRepository->save($operation);
        }

        $matcherRepository = new Fixture\Repository\Matcher();
        foreach ($matchers as $matcher) {
            $matcherRepository->save($matcher);
        }

        self::assertEqualsCanonicalizing($expected, (new FromDatabase($operationRepository, $matcherRepository))
            ->getOperationConfig($operationId));
    }

    /**
     * @return \Generator<array{
     *     0: ?OperationConfig,
     *     1: ?Model\Operation,
     *     2: Model\Matcher[],
     *     3: string,
     * }>
     */
    public static function provideConfigsToLocate(): \Generator
    {
        yield 'no config, returns null' => [
            null,
            null,
            [],
            'find-pet-by-id',
        ];

        yield 'config with array body' => [
            [
                'operationId' => 'listPets',
                'matchers' => [],
                'default' => [
                    'response' => [
                        'code' => 200,
                        'headers' => ['Cache-control' => ['max=age=180', 'public']],
                        'body' => [
                            'type' => 'application/json',
                            'content' => [['id' => 5, 'name' => 'Blink'], ['id' => 6, 'name' => 'Harley']],
                        ],
                    ],
                ],
            ],
            new Model\Operation(
                'listPets',
                200,
                ['Cache-control' => ['max=age=180', 'public']],
                [
                    'type' => 'application/json',
                    'content' => [['id' => 5, 'name' => 'Blink'], ['id' => 6, 'name' => 'Harley']],
                ],
            ),
            [],
            'listPets',
        ];
    }
}
