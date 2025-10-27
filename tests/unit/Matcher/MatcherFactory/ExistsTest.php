<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher\MatcherFactory;

use Membrane\MockServer\Matcher\MatcherFactory\Exists;
use Membrane\MockServer\Mocking\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Exists
 */
#[UsesClass(Field::class)]
#[UsesClass(\Membrane\MockServer\Matcher\Matcher\Exists::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Exists::class)]
final class ExistsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        \Membrane\MockServer\Matcher\Matcher\Exists $expected,
        array $config,
    ): void {
        self::assertEquals($expected, (new Exists())
            ->create($config));
    }

    /* @return \Generator<array{0: \Membrane\MockServer\Matcher\Matcher\Exists, 1: Config}> */
    public static function provideConfigs(): \Generator
    {
        yield '1 field' => [
            new \Membrane\MockServer\Matcher\Matcher\Exists(
                new Field('id', 'path'),
            ),
            ['fields' => [['path', 'id']]],
        ];
        yield '3 fields' => [
            new \Membrane\MockServer\Matcher\Matcher\Exists(
                new Field('species', 'path', 'pet'),
                new Field('age', 'path', 'pet'),
                new Field('limit', 'query'),
            ),
            ['fields' => [
                ['path', 'pet', 'species'],
                ['path', 'pet', 'age'],
                ['query', 'limit'],
            ]],
        ];
    }
}
