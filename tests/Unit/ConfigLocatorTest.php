<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Membrane\MockServer\ConfigLocator;
use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;
use Membrane\MockServer\Handler;
use Membrane\MockServer\Matcher;
use Membrane\MockServer\Matcher\Equals;
use Membrane\MockServer\Tests\Fixture\Matcher\AlwaysMatch;
use Membrane\MockServer\Tests\Fixture\Matcher\NeverMatch;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-import-type OperationConfig from ConfigLocator
 * @phpstan-import-type MatcherConfig from ConfigLocator
 * @phpstan-import-type ResponseConfig from ConfigLocator
 */
#[UsesClass(DTO::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(ConfigLocator::class)]
final class ConfigLocatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param array{
     *     matchers: array<array{response: ResponseInterface, matcher: Matcher}>,
     *     default?: array{response: ResponseInterface},
     * } $expected
     * @param array<string, OperationConfig> $config
     */
    #[Test]
    #[DataProvider('provideConfigsToLocate')]
    public function itLocatesOperationConfigs(
        ?array $expected,
        array $config,
        string $operationId,
    ): void {
        $actual = (new ConfigLocator($config))
            ->getOperationConfig($operationId);

        if (!isset($expected[$operationId])) {
            self::assertNull($actual[$operationId]); // TODO is this right?
        }

        foreach ($expected[$operationId]['matchers'] as $index => $expectedMatchCase) {
            $actualMatchCase = $actual[$operationId]['matchers'][$index];
            self::assertEquals(
                $expectedMatchCase['matcher'],
                $actualMatchCase['matcher'],
            );
            self::assertResponseEquals(
                $expectedMatchCase['response'],
                $actualMatchCase['response'],
            );
        }
    }

    /**
     * @return \Generator<array{
     *     0: null|array{
     *          matchers: array<array{response: ResponseInterface, matcher: Matcher}>,
     *          default?: array{response: ResponseInterface},
     *     },
     *     1: array<string, OperationConfig>,
     *     2: string,
     * }>
     */
    public static function provideConfigsToLocate(): \Generator
    {
        yield 'no config, returns null' => [null, [], 'example'];

        yield 'default int response' => [
            ['default' => ['response' => new Response(200)], 'matchers' => []],
            ['example' => ['default' => ['response' => 200]]],
            'example',
        ];

        yield 'default array response; body as string' => [
            [
                'default' => ['response' => new Response(
                    201,
                    ['is-test' => true],
                    'Hello, World!',
                )],
                'matchers' => [],
            ],
            ['example' => [
                'default' => ['response' => [
                    'code' => 201,
                    'headers' => ['is-test' => true],
                    'body' => 'Hello, World!',
                ]],
            ]],
            'example',
        ];
    }

    public static function assertResponseEquals(
        ResponseInterface $expected,
        ResponseInterface $actual,
    ): void {
        self::assertSame(
            $expected->getStatusCode(),
            $actual->getStatusCode(),
        );
        self::assertSame(
            $expected->getHeaders(),
            $actual->getHeaders(),
        );
        self::assertSame(
            (string) $expected->getBody(),
            (string) $actual->getBody(),
        );
    }
}
