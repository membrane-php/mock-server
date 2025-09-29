<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;
use Membrane\MockServer\Handler;
use Membrane\MockServer\Matcher\Equals;
use Membrane\MockServer\Tests\Fixture\Matcher\AlwaysMatch;
use Membrane\MockServer\Tests\Fixture\Matcher\NeverMatch;
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
        yield 'no config, returns null'
            => [null, [], new DTO(['request' => ['operationId' => 'example']])];

        yield 'no matchers, default applies' => [
            new Response(200),
            ['example' => ['default' => ['response' => new Response(200)]]],
            new DTO(['request' => ['operationId' => 'example']]),
        ];

        yield 'match takes priority over default' => [
            new Response(200),
            [
                'example' => [
                    'matchers' => [[
                        'matcher' => new AlwaysMatch(),
                        'response' => new Response(200),
                    ]],
                    'default' => [
                        'response' => new Response(400),
                    ],
                ],
            ],
            new DTO(['request' => ['operationId' => 'example']]),
        ];

        yield 'two matches, first match, first serve' => [
            new Response(200),
            [
                'example' => [
                    'matchers' => [[
                        'matcher' => new AlwaysMatch(),
                        'response' => new Response(200),
                    ], [
                        'matcher' => new AlwaysMatch(),
                        'response' => new Response(401),
                    ]],
                    'default' => [
                        'response' => new Response(400),
                    ],
                ],
            ],
            new DTO(['request' => ['operationId' => 'example']]),
        ];

        yield 'avoids non-matches, first match, first serve' => [
            new Response(200),
            [
                'example' => [
                    'matchers' => [[
                        'matcher' => new NeverMatch(),
                        'response' => new Response(401),
                    ], [
                        'matcher' => new AlwaysMatch(),
                        'response' => new Response(200),
                    ], [
                        'matcher' => new AlwaysMatch(),
                        'response' => new Response(402),
                    ]],
                    'default' => [
                        'response' => new Response(403),
                    ],
                ],
            ],
            new DTO(['request' => ['operationId' => 'example']]),
        ];

        yield 'matches against path properties' => [
            new Response(200),
            [
                'example' => [
                    'matchers' => [[
                        'matcher' => new Equals(new Field('field', 'path'), 'Howdy, planet!'),
                        'response' => new Response(401),
                    ], [
                        'matcher' => new Equals(new Field('field', 'path'), 'Hello, world!'),
                        'response' => new Response(200),
                    ]],
                    'default' => [
                        'response' => new Response(403),
                    ],
                ],
            ],
            new DTO(['request' => ['operationId' => 'example', 'path' => ['field' => 'Hello, world!']]]),
        ];
    }
}
