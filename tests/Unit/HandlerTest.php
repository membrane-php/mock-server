<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Membrane\MockServer\DTO;
use Membrane\MockServer\Handler;
use Membrane\MockServer\Tests\Fixture\Matcher\AlwaysMatch;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Http\Message\ResponseInterface;

#[UsesClass(DTO::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Handler::class)]
final class HandlerTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @param array<string, mixed> $config
     */
    #[Test]
    #[DataProvider('provideDTOsToHandle')]
    public function itHandlesDTOs(
        ?ResponseInterface $expected,
        array $config,
        DTO $dto,
    ): void {
        self::assertEquals($expected, (new Handler($config))($dto));
    }

    /**
     * @return \Generator<array{
     *     0: ?ResponseInterface,
     *     1: array<string, mixed>,
     *     2: DTO,
     * }>
     */
    public static function provideDTOsToHandle(): \Generator
    {
        yield 'no data' => [null, [], new DTO(['request' => [
            'operationId' => 'example'
        ]])];

        yield 'default response' => [
            new Response(200),
            [
                'example' => [
                    'default' => ['response' => new Response(200)]
                ],
            ],
            new DTO(['request' => ['operationId' => 'example']]),
        ];

        yield 'matcher response takes priority over default' => [
            new Response(200),
            [
                'example' => [
                    'matchers' => [
                        [
                            'matcher' => new AlwaysMatch(),
                            'response' => new Response(200),
                        ]
                    ],
                    'default' => ['response' => new Response(400)],
                ],
            ],
            new DTO(['request' => ['operationId' => 'example']]),
        ];
    }
}
