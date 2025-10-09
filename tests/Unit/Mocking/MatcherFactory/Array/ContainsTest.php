<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking\MatcherFactory\Array;

use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher;
use Membrane\MockServer\Mocking\MatcherFactory\Array\Contains;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Contains
 */
#[UsesClass(Field::class)]
#[UsesClass(Matcher\Array\Contains::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Contains::class)]
final class ContainsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        Matcher\Array\Contains $expected,
        array $config,
    ): void {
        self::assertEquals($expected, (new Contains())
            ->create($config));
    }

    /* @return \Generator<array{0: Matcher\Array\Contains, 1: Config}> */
    public static function provideConfigs(): \Generator
    {
        yield 'mixed values in "difficulty" in "query"' => [
            new Matcher\Array\Contains(
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
