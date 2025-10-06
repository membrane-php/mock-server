<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\MatcherFactory;

use League\Container\Container;
use Membrane\MockServer\DTO;
use Membrane\MockServer\Matcher;
use Membrane\MockServer\MatcherFactory;
use Membrane\MockServer\MatcherFactory\AllOf;
use Membrane\MockServer\Tests\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from AllOf
 */
#[UsesClass(Fixture\Matcher::class)]
#[UsesClass(Fixture\MatcherFactory::class)]
#[UsesClass(Matcher\AllOf::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(AllOf::class)]
final class AllOfTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param array<class-string<MatcherFactory>, MatcherFactory> $entries
     * @param array<string, class-string<MatcherFactory>> $aliases
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        Matcher\AllOf $expected,
        array $entries,
        array $aliases,
        array $config,
    ): void {
        $container = new Container();
        foreach ($entries as $id => $entry) {
            $container->add($id, $entry);
        }

        self::assertEquals($expected, (new AllOf($container, $aliases))
            ->create($config));
    }

    /**
     * @return \Generator<array{
     *     0: Matcher\AllOf,
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
            creates: $goodDayMatcher
        );
        $helloFactory = new Fixture\MatcherFactory(
            expects: $helloAlias,
            creates: $helloMatcher
        );

        yield 'one sub-matcher' => [
            new Matcher\AllOf(
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
                    ['type' => 'hello', 'parameters' => $helloAlias],
                ],
            ],
        ];


        yield 'three sub-matchers' => [
            new Matcher\AllOf(
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
                    ['type' => 'hello', 'parameters' => $helloAlias],
                    ['type' => 'howdy', 'parameters' => $howdyAlias],
                    ['type' => 'good-day', 'parameters' => $goodDayAlias],
                ],
            ],
        ];
    }
}
