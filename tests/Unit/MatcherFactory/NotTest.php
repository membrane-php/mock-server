<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\MatcherFactory;

use League\Container\Container;
use Membrane\MockServer\DTO;
use Membrane\MockServer\Matcher;
use Membrane\MockServer\MatcherFactory;
use Membrane\MockServer\MatcherFactory\Not;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Not
 */
#[UsesClass(Fixture\Matcher::class)]
#[UsesClass(Fixture\MatcherFactory::class)]
#[UsesClass(Matcher\Not::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Not::class)]
final class NotTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param array<class-string<MatcherFactory>, MatcherFactory> $entries
     * @param array<string, class-string<MatcherFactory>> $aliases
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        Matcher\Not $expected,
        array $entries,
        array $aliases,
        array $config,
    ): void {
        $container = new Container();
        foreach ($entries as $id => $entry) {
            $container->add($id, $entry);
        }

        self::assertEquals($expected, (new Not($container, $aliases))
            ->create($config));
    }

    /**
     * @return \Generator<array{
     *     0: Matcher\Not,
     *     1: array<string, MatcherFactory>,
     *     2: array<string, string>,
     *     3: Config,
     * }>
     */
    public static function provideConfigs(): \Generator
    {
        $helloAlias = ['greeting' => 'Hello, world!'];
        $howdyAlias = ['greeting' => 'Howdy, planet!'];

        $helloMatcher = new Fixture\Matcher(expects: new DTO(['Hello']));
        $howdyMatcher = new Fixture\Matcher(expects: new DTO(['Howdy']));

        $helloFactory = new Fixture\MatcherFactory(
            expects: $helloAlias,
            creates: $helloMatcher
        );
        $howdyFactory = new Fixture\MatcherFactory(
            expects: $howdyAlias,
            creates: $howdyMatcher,
        );

        yield 'wraps another matcher with the correct config' => [
            new Matcher\Not($helloMatcher),
            [
                'HowdyPlanet' => $howdyFactory,
                'HelloWorld' => $helloFactory,
            ],
            [
                'howdy' => 'HowdyPlanet',
                'hello' => 'HelloWorld',
            ],
            [
                'matcher' => ['type' => 'hello', 'args' => $helloAlias],
            ],
        ];
    }
}
