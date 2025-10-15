<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking;

use GuzzleHttp\Psr7\Response;
use Membrane\MockServer\Mocking\ResponseFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-import-type ResponseConfig from \Membrane\MockServer\Mocking\Module
 */
#[\PHPUnit\Framework\Attributes\CoversClass(ResponseFactory::class)]
final class ResponseFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param ResponseConfig|int $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesResponses(
        ResponseInterface $expected,
        array|int $config,
    ): void {
        self::assertResponseEquals($expected, (new ResponseFactory())
            ->create($config));
    }

    /**
     * @return \Generator<array{
     *     0: ResponseInterface,
     *     1: ResponseConfig|int
     *  }>
     */
    public static function provideConfigs(): \Generator
    {
        yield 'minimal 200' => [new Response(200), 200];
        yield 'minimal 404' => [new Response(404), 404];

        yield '201 with raw string body' => [
            new Response(
                201,
                ['greeting' => 'Hello, World!'],
                'Howdy, Planet!',
            ),
            [
                'code' => 201,
                'headers' => ['greeting' => 'Hello, World!'],
                'body' => 'Howdy, Planet!',
            ],
        ];

        yield '202 with application/json body' => [
            new Response(202, [], '{"greeting":"Hello, World!"}'),
            [
                'code' => 202,
                'body' => [
                    'type' => 'application/json',
                    'content' => ['greeting' => 'Hello, World!'],
                ],
            ],
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
