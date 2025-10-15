<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking\MatcherFactory;

use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher;
use Membrane\MockServer\Mocking\MatcherFactory\GreaterThan;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from GreaterThan
 */
#[UsesClass(Field::class)]
#[UsesClass(Matcher\GreaterThan::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(GreaterThan::class)]
final class GreaterThanTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        Matcher\GreaterThan $expected,
        array $config,
    ): void {
        self::assertEquals($expected, (new GreaterThan())
            ->create($config));
    }

    /* @return \Generator<array{0: Matcher\GreaterThan, 1: Config}> */
    public static function provideConfigs(): \Generator
    {
        yield 'implicitly >= 1' => [
            new Matcher\GreaterThan(
                new Field('age', 'path', 'pet'),
                1,
                true,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => 1,
            ],
        ];
        yield 'implicitly >= "1"' => [
            new Matcher\GreaterThan(
                new Field('age', 'path', 'pet'),
                1,
                true,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => '1',
            ],
        ];
        yield 'implicitly >= 3.14' => [
            new Matcher\GreaterThan(
                new Field('age', 'path', 'pet'),
                3.14,
                true,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => 3.14,
            ],
        ];

        yield 'explicitly >= 2' => [
            new Matcher\GreaterThan(
                new Field('age', 'path', 'pet'),
                2,
                true,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => 2,
                'inclusive' => true,
            ],
        ];
        yield 'explicitly >= "2"' => [
            new Matcher\GreaterThan(
                new Field('age', 'path', 'pet'),
                2,
                true,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => '2',
                'inclusive' => true,
            ],
        ];
        yield 'explicitly >= 9.81' => [
            new Matcher\GreaterThan(
                new Field('age', 'path', 'pet'),
                9.81,
                true,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => 9.81,
                'inclusive' => true,
            ],
        ];

        yield '> 3' => [
            new Matcher\GreaterThan(
                new Field('age', 'path', 'pet'),
                3,
                false,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => 3,
                'inclusive' => false,
            ],
        ];
        yield '> "3"' => [
            new Matcher\GreaterThan(
                new Field('age', 'path', 'pet'),
                3,
                false,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => '3',
                'inclusive' => false,
            ],
        ];
    }
}
