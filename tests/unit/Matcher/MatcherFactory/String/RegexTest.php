<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher\MatcherFactory\String;

use Membrane\MockServer\Matcher\MatcherFactory\String\Regex;
use Membrane\MockServer\Mocking\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Regex
 */
#[UsesClass(Field::class)]
#[UsesClass(\Membrane\MockServer\Matcher\Matcher\String\Regex::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Regex::class)]
final class RegexTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        \Membrane\MockServer\Matcher\Matcher\String\Regex $expected,
        array $config,
    ): void {
        self::assertEquals($expected, (new Regex())
            ->create($config));
    }

    /* @return \Generator<array{0: \Membrane\MockServer\Matcher\Matcher\String\Regex, 1: Config}> */
    public static function provideConfigs(): \Generator
    {
        yield 'Capitalized "name" in "path"' => [
            new \Membrane\MockServer\Matcher\Matcher\String\Regex(
                new Field('name', 'path'),
                '#^[A-Z][a-z]*$#',
            ),
            [
                'field' => ['path', 'name'],
                'pattern' => '#^[A-Z][a-z]*$#',
            ],
        ];
    }
}
