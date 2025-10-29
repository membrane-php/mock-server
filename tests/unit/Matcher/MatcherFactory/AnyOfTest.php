<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher\MatcherFactory;

use League\Container\Container;
use Membrane\MockServer\Matcher\MatcherFactory;
use Membrane\MockServer\Matcher\MatcherFactory\AnyOf;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from AnyOf
 */
#[UsesClass(Fixture\Matcher::class)]
#[UsesClass(Fixture\MatcherFactory::class)]
#[UsesClass(\Membrane\MockServer\Matcher\Matcher\AnyOf::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(AnyOf::class)]
final class AnyOfTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param array<class-string<MatcherFactory>, MatcherFactory> $entries
     * @param array<string, class-string<MatcherFactory>> $aliases
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        \Membrane\MockServer\Matcher\Matcher\AnyOf $expected,
        array $entries,
        array $aliases,
        array $config,
    ): void {
        $container = new Container();
        foreach ($entries as $id => $entry) {
            $container->add($id, $entry);
        }

        self::assertEquals($expected, (new AnyOf($container, $aliases))
            ->create($config));
    }

    /**
     * @return \Generator<array{
     *     0: \Membrane\MockServer\Matcher\Matcher\AnyOf,
     *     1: array<string, MatcherFactory>,
     *     2: array<string, string>,
     *     3: Config,
     * }>
     */
    public static function provideConfigs(): \Generator
    {
        $helloAlias = ['greeting' => 'Hello, world!'];
        $howdyAlias = ['greeting' => 'Howdy, planet!'];
        $goodDayAlias = ['greeting' => 'Good day, globe!'];

        $helloMatcher = new Fixture\Matcher(expects: new DTO(['Hello']));
        $howdyMatcher = new Fixture\Matcher(expects: new DTO(['Howdy']));
        $goodDayMatcher = new Fixture\Matcher(expects: new DTO(['Good day']));

        $howdyFactory = new Fixture\MatcherFactory(
            expects: $howdyAlias,
            creates: $howdyMatcher,
        );
        $goodDayFactory = new Fixture\MatcherFactory(
            expects: $goodDayAlias,
            creates: $goodDayMatcher,
        );
        $helloFactory = new Fixture\MatcherFactory(
            expects: $helloAlias,
            creates: $helloMatcher,
        );

        yield 'one sub-matcher' => [
            new \Membrane\MockServer\Matcher\Matcher\AnyOf(
                $helloMatcher,
            ),
            [
                'HelloWorld' => $helloFactory,
            ],
            [
                'hello' => 'HelloWorld',
            ],
            [
                'matchers' => [
                    ['type' => 'hello', 'args' => $helloAlias],
                ],
            ],
        ];


        yield 'three sub-matchers' => [
            new \Membrane\MockServer\Matcher\Matcher\AnyOf(
                $helloMatcher,
                $howdyMatcher,
                $goodDayMatcher,
            ),
            [
                'HelloWorld' => $helloFactory,
                'HowdyPlanet' => $howdyFactory,
                'GoodDayGlobe' => $goodDayFactory,
            ],
            [
                'hello' => 'HelloWorld',
                'howdy' => 'HowdyPlanet',
                'good-day' => 'GoodDayGlobe',
            ],
            [
                'matchers' => [
                    ['type' => 'hello', 'args' => $helloAlias],
                    ['type' => 'howdy', 'args' => $howdyAlias],
                    ['type' => 'good-day', 'args' => $goodDayAlias],
                ],
            ],
        ];
    }
}
