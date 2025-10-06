<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\MatcherFactory;

use Membrane\MockServer\Matcher;
use Membrane\MockServer\MatcherFactory\LessThan;
use Membrane\MockServer\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from LessThan
 */
#[UsesClass(Field::class)]
#[UsesClass(Matcher\LessThan::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(LessThan::class)]
final class LessThanTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        Matcher\LessThan $expected,
        array $config,
    ): void {
        self::assertEquals($expected, (new LessThan())
            ->create($config));
    }

    /* @return \Generator<array{0: Matcher\LessThan, 1: Config}> */
    public static function provideConfigs(): \Generator
    {
        yield 'implicitly <= 1' => [
            new Matcher\LessThan(
                new Field('age', 'path', 'pet'),
                1,
                true,
            ),
            [
                'field' => ['path', 'pet', 'age'],
                'limit' => 1,
            ],
        ];

        yield 'explicitly <= 2' => [
            new Matcher\LessThan(
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

        yield '< 3' => [
            new Matcher\LessThan(
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
    }
}
