<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher\MatcherFactory\Array;

use Membrane\MockServer\Matcher\MatcherFactory\Array\Contains;
use Membrane\MockServer\Mocking\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Contains
 */
#[UsesClass(Field::class)]
#[UsesClass(\Membrane\MockServer\Matcher\Matcher\Array\Contains::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Contains::class)]
final class ContainsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        \Membrane\MockServer\Matcher\Matcher\Array\Contains $expected,
        array $config,
    ): void {
        self::assertEquals($expected, (new Contains())
            ->create($config));
    }

    /* @return \Generator<array{0: \Membrane\MockServer\Matcher\Matcher\Array\Contains, 1: Config}> */
    public static function provideConfigs(): \Generator
    {
        yield 'mixed values in "difficulty" in "query"' => [
            new \Membrane\MockServer\Matcher\Matcher\Array\Contains(
                new Field('difficulty', 'query'),
                'easy',
                'as',
                1,
                2,
                3,
            ),
            [
                'field' => ['query', 'difficulty'],
                'values' => ['easy', 'as', 1, 2, 3],
            ],
        ];
    }
}
