<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit;

use League\Container\Container;
use Membrane\MockServer\FactoryLocator;
use Membrane\MockServer\MatcherFactory;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-import-type AliasesConfig from FactoryLocator
 * @phpstan-import-type FactoryConfig from MatcherFactory
 */
#[\PHPUnit\Framework\Attributes\CoversClass(FactoryLocator::class)]
final class FactoryLocatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param AliasesConfig $aliases
     * @param FactoryConfig $config
     */
    #[Test]
    #[DataProvider('provideConfigsToLocate')]
    public function itLocatesFactoryFromConfig(
        MatcherFactory $expected,
        ContainerInterface $container,
        array $aliases,
        array $config,
    ): void {
        $factoryLocator = new FactoryLocator($container, $aliases);
        self::assertSame($expected, $factoryLocator->locate($config));
    }

    /**
     * @return \Generator<array{
     *     0: MatcherFactory,
     *     1: ContainerInterface,
     *     2: AliasesConfig,
     *     3: FactoryConfig,
     * }>
     */
    public static function provideConfigsToLocate(): \Generator
    {
        yield 'matches only configured matcher' => (function () {
            $matcherConfig = ['greeting' => 'Hello, World!'];
            $config = [
                'type' => 'my-matcher',
                'parameters' => $matcherConfig,
            ];

            $factory = new Fixture\MatcherFactory(
                expects: $matcherConfig,
                creates: new Fixture\Matcher(),
            );

            $aliases = ['my-matcher' => $factory::class];

            $container = new Container();
            $container->add($factory::class, $factory);

            return [$factory, $container, $aliases, $config];
        })();
    }
}
