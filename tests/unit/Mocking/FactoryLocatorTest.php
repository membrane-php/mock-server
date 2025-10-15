<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking;

use League\Container\Container;
use Membrane\MockServer\Mocking\FactoryLocator;
use Membrane\MockServer\Mocking\MatcherFactory;
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
    #[Test]
    public function itFailsIfItFindsNonMatcherFactory(): void
    {
        $container = new Container();
        $container->add('\Acme\Foo', $container);

        $sut = new FactoryLocator($container, ['foo' => '\Acme\Foo']);

        self::expectException(\RuntimeException::class);

        $sut->locate('foo');
    }

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
        string $alias,
    ): void {
        $factoryLocator = new FactoryLocator($container, $aliases);
        self::assertSame($expected, $factoryLocator->locate($alias));
    }

    /**
     * @return \Generator<array{
     *     0: MatcherFactory,
     *     1: ContainerInterface,
     *     2: AliasesConfig,
     *     3: string,
     * }>
     */
    public static function provideConfigsToLocate(): \Generator
    {
        yield 'matches only configured matcher' => (function () {
            $matcherConfig = ['greeting' => 'Hello, World!'];
            $alias = 'my-matcher';

            $factory = new Fixture\MatcherFactory(
                expects: $matcherConfig,
                creates: new Fixture\Matcher(),
            );

            $aliases = [$alias => $factory::class];

            $container = new Container();
            $container->add($factory::class, $factory);

            return [$factory, $container, $aliases, $alias];
        })();
    }
}
