<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking;

use GuzzleHttp\Psr7\Response;
use League\Container\Container;
use Membrane\MockServer\Matcher\FactoryLocator;
use Membrane\MockServer\Matcher\Matcher\Equals;
use Membrane\MockServer\Mocking\ConfigLocator\FromApplicationConfig;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Handler;
use Membrane\MockServer\Mocking\ResponseFactory;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Mocking\Module
 * @phpstan-import-type AliasesConfig from \Membrane\MockServer\Mocking\Module
 */
#[UsesClass(DTO::class)]
#[UsesClass(Field::class)]
#[UsesClass(Equals::class)]
#[UsesClass(Fixture\Matcher::class)]
#[UsesClass(Fixture\MatcherFactory::class)]
#[UsesClass(FromApplicationConfig::class)]
#[UsesClass(FactoryLocator::class)]
#[UsesClass(ResponseFactory::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Handler::class)]
final class HandlerTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function itFailsWithoutOperationId(): void
    {
        $dtoWithoutOperationId = new DTO(['request' => []]);

        $sut = new Handler(
            new FromApplicationConfig([]),
            new FactoryLocator(new Container(), []),
            new ResponseFactory(),
        );

        self::expectException(\RuntimeException::class);

        $sut($dtoWithoutOperationId);
    }

    /**
     * @param array<string, OperationConfig> $operationMap
     * @param AliasesConfig $aliases
     */
    #[Test]
    #[DataProvider('provideDTOsToHandle')]
    public function itHandlesDTOs(
        ?ResponseInterface $expected,
        array $operationMap,
        ContainerInterface $container,
        array $aliases,
        DTO $dto,
    ): void {
        $configLocator = new FromApplicationConfig($operationMap);

        $factoryLocator = new FactoryLocator($container, $aliases);

        $responseFactory = new ResponseFactory();

        $sut = new Handler($configLocator, $factoryLocator, $responseFactory);

        self::assertEquals($expected, $sut($dto));
    }

    /**
     * @return \Generator<array{
     *     0: ?ResponseInterface,
     *     1: array<string, OperationConfig>,
     *     2: ContainerInterface,
     *     3: AliasesConfig,
     *     4: DTO,
     * }>
     */
    public static function provideDTOsToHandle(): \Generator
    {
        yield 'empty config, returns null' => [
            null,
            ['example' => []],
            new Container(),
            [],
            new DTO(['request' => ['operationId' => 'example']]),
        ];

        yield 'no matchers, default applies' => [
            new Response(200),
            ['example' => ['default' => ['response' => 200]]],
            new Container(),
            [],
            new DTO(['request' => ['operationId' => 'example']]),
        ];

        yield 'match takes priority over default' => (function () {
            $alias = 'my-matcher';
            $dto = new DTO(['request' => ['operationId' => 'example']]);
            $matcherConfig = ['string-arg' => 'Hello, World!'];
            $matcherFactoryConfig = ['type' => $alias, 'args' => $matcherConfig];

            $matcherFactory = new Fixture\MatcherFactory(
                expects: $matcherConfig,
                creates: new Fixture\Matcher(expects: $dto, matches: true),
            );

            $container = new Container();
            $container->add($matcherFactory::class, $matcherFactory);

            $operationMap = [
                'example' => [
                    'matchers' => [
                        [
                            'matcher' => $matcherFactoryConfig,
                            'response' => 200,
                        ],
                    ],
                    'default' => [
                        'response' => 400,
                    ],
                ],
            ];

            return [
                new Response(200),
                $operationMap,
                $container,
                [$alias => $matcherFactory::class],
                $dto,
            ];
        })();


        yield 'two matches, first match, first serve' => (function () {
            $alias = 'a';
            $dto = new DTO(['request' => ['operationId' => 'example']]);
            $matcherConfig = ['string-arg' => 'Hello, World!'];
            $matcherFactoryConfig = ['type' => $alias, 'args' => $matcherConfig];

            $matcherFactory = new Fixture\MatcherFactory(
                expects: $matcherConfig,
                creates: new Fixture\Matcher(expects: $dto, matches: true),
            );

            $container = new Container();
            $container->add($matcherFactory::class, $matcherFactory);

            $operationMap = [
                'example' => [
                    'matchers' => [
                        [
                            'matcher' => $matcherFactoryConfig,
                            'response' => 200,
                        ],
                        [
                            'matcher' => ['type' => 'b', 'args' => []],
                            'response' => 300,
                        ],
                    ],
                    'default' => [
                        'response' => 400,
                    ],
                ],
            ];

            return [
                new Response(200),
                $operationMap,
                $container,
                [$alias => $matcherFactory::class],
                $dto,
            ];
        })();

        yield 'avoids non-matches, first match, first serve' => (function () {
            $dto = new DTO(['request' => ['operationId' => 'example']]);

            $aliasA = 'a';
            $aliasB = 'b';

            $matcherConfigA = ['string-arg' => 'Hello, World!'];
            $matcherConfigB = ['int-arg' => 3.14];

            $factoryConfigA = ['type' => $aliasA, 'args' => $matcherConfigA];
            $factoryConfigB = ['type' => $aliasB, 'args' => $matcherConfigB];

            $matcherFactoryA = new Fixture\MatcherFactory(
                expects: $matcherConfigA,
                creates: new Fixture\Matcher($dto, matches: false),
            );
            $matcherFactoryB = new Fixture\MatcherFactory(
                expects: $matcherConfigB,
                creates: new Fixture\Matcher($dto, matches: true),
            );

            $container = new Container();
            $container->add($matcherFactoryA::class . 'A', $matcherFactoryA);
            $container->add($matcherFactoryB::class . 'B', $matcherFactoryB);

            $operationMap = [
                'example' => [
                    'matchers' => [
                        [
                            'matcher' => $factoryConfigA,
                            'response' => 200,
                        ],
                        [
                            'matcher' => $factoryConfigB,
                            'response' => 201,
                        ],
                    ],
                    'default' => [
                        'response' => 202,
                    ],
                ],
            ];

            return [
                new Response(201),
                $operationMap,
                $container,
                [
                    $aliasA => $matcherFactoryA::class . 'A',
                    $aliasB => $matcherFactoryB::class . 'B',
                ],
                $dto,
            ];
        })();
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
