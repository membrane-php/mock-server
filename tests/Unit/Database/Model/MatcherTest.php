<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Database\Model;

use Membrane\MockServer\Database\Model\Matcher;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * @phpstan-import-type MatcherFactoryConfig from \Membrane\MockServer\Mocking\Module
 * @phpstan-import-type ResponseConfig from \Membrane\MockServer\Mocking\Module
 */
#[\PHPUnit\Framework\Attributes\CoversClass(Matcher::class)]
final class MatcherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param array{matcher: MatcherFactoryConfig, response: ResponseConfig|int} $expected
     * @param array<string, string|list<string>> $headers
     * @param array<string, mixed> $args
     * @param array<mixed>|string $body
     */
    #[Test]
    #[DataProvider('provideConfigsToLocate')]
    public function itIsJsonSerializable(
        array $expected,
        string $alias,
        array $args,
        int $responseCode,
        array $headers,
        array|string $body,
    ): void {
        self::assertEqualsCanonicalizing($expected, (new Matcher(
            'abc123',
            'listPets',
            $alias,
            $args,
            $responseCode,
            $headers,
            $body,
        ))->jsonSerialize());
    }

    /**
     * @return \Generator<array{
     *     0: array{matcher: MatcherFactoryConfig, response: ResponseConfig|int},
     *     1: string,
     *     2: list<mixed>,
     *     3: int,
     *     4: array<string, string|list<string>>,
     *     5: array<mixed>|string,
     * }>
     */
    public static function provideConfigsToLocate(): \Generator
    {
        yield 'MVP; no args, empty headers, empty string body' => [
            [
                'matchers' => [
                    'type' => 'equals',
                    'args' => ['value' => 5, 'field' => ['path', 'id']],
                ],
                'response' => [
                    'code' => 200,
                    'headers' => [],
                    'body' => '',
                ],
            ],
            'equals',
            ['value' => 5, 'field' => ['path', 'id']],
            200,
            [],
            '',
        ];

        yield 'args, headers and string body' => [
            [
                'matcher' => [
                    'type' => 'less-than',
                    'args' => ['value' => 5, 'inclusive' => false],
                ],
                'response' => [
                    'code' => 404,
                    'headers' => ['Cache-control' => ['age=60']],
                    'body' => 'Hello, World!',
                ],
            ],
            'less-than',
            ['value' => 5, 'inclusive' => false],
            404,
            ['Cache-control' => ['age=60']],
            'Hello, World!',
        ];

        yield 'headers and array body' => [
            [
                'matcher' => [
                    'type' => 'greater-than',
                    'args' => ['value' => 5, 'inclusive' => false],
                ],
                'response' => [
                    'code' => 418,
                    'headers' => ['Cache-control' => ['age=60', 'must-revalidate']],
                    'body' => '{"id":5,"species":"cat","name":"Blink"}',
                ],
            ],
            'greater-than',
            ['value' => 5, 'inclusive' => false],
            418,
            ['Cache-control' => ['age=60', 'must-revalidate']],
            ['id' => 5, 'species' => 'cat', 'name' => 'Blink'],
        ];
    }
}
