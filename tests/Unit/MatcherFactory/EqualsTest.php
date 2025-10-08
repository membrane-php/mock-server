<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\MatcherFactory;

use Membrane\MockServer\Matcher;
use Membrane\MockServer\MatcherFactory\Equals;
use Membrane\MockServer\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Equals
 */
#[UsesClass(Field::class)]
#[UsesClass(Matcher\Equals::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Equals::class)]
final class EqualsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        Matcher\Equals $expected,
        array $config,
    ): void {
        self::assertEquals($expected, (new Equals())
            ->create($config));
    }

    /* @return \Generator<array{0: Matcher\Equals, 1: Config}> */
    public static function provideConfigs(): \Generator
    {
        yield '"id" in "path" equals 1' => [
            new Matcher\Equals(
                new Field('id', 'path'),
                1,
            ),
            [
                'field' => ['path', 'id'],
                'value' => 1,
            ],
        ];
        yield '"pet": "id" in "path" equals 2' => [
            new Matcher\Equals(
                new Field('id', 'path', 'pet'),
                2,
            ),
            [
                'field' => ['path', 'pet', 'id'],
                'value' => 2,
            ],
        ];
    }
}
