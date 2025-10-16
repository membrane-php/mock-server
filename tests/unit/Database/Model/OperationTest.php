<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Database\Model;

use Membrane\MockServer\Database\Model\Operation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Mocking\Module
 */
#[\PHPUnit\Framework\Attributes\CoversClass(Operation::class)]
final class OperationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param OperationConfig $expected
     * @param array<string, string|list<string>> $headers
     * @param array<mixed>|string $body
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itIsJsonSerializable(
        array $expected,
        string $operationId,
        int $defaultResponseCode,
        array $headers,
        array|string $body,
    ): void {
        self::assertEqualsCanonicalizing($expected, (new Operation(
            $operationId,
            $defaultResponseCode,
            $headers,
            $body,
        ))->jsonSerialize());
    }

    /**
     * @return \Generator<array{
     *     0: ?OperationConfig,
     *     1: string,
     *     2: int,
     *     3: array<string, string|list<string>>,
     *     4: array<mixed>|string,
     * }>
     */
    public static function provideConfigs(): \Generator
    {
        yield 'MVP; empty headers, empty string body' => [
            [
                'operationId' => 'find-pet-by-id',
                'default' => [
                    'response' => [
                        'code' => 200,
                        'headers' => [],
                        'body' => '',
                    ],
                ],
            ],
            'find-pet-by-id',
            200,
            [],
            '',
        ];

        yield 'headers and string body' => [
            [
                'operationId' => 'find-pet-by-id',
                'default' => [
                    'response' => [
                        'code' => 404,
                        'headers' => ['Cache-control' => ['age=60']],
                        'body' => 'Hello, World!',
                    ],
                ],
            ],
            'find-pet-by-id',
            404,
            ['Cache-control' => ['age=60']],
            'Hello, World!',
        ];

        yield 'headers and array body' => [
            [
                'operationId' => 'find-pet-by-id',
                'default' => [
                    'response' => [
                        'code' => 418,
                        'headers' => ['Cache-control' => ['age=60', 'must-revalidate']],
                        'body' => '{"id":52,"species":"cat","name":"Blink"}',
                    ],
                ],
            ],
            'find-pet-by-id',
            418,
            ['Cache-control' => ['age=60', 'must-revalidate']],
            ['id' => 52, 'species' => 'cat', 'name' => 'Blink'],
        ];
    }
}
