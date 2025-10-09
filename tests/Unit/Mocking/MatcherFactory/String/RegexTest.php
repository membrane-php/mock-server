<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking\MatcherFactory\String;

use Membrane\MockServer\Mocking\Field;
use Membrane\MockServer\Mocking\Matcher;
use Membrane\MockServer\Mocking\MatcherFactory\String\Regex;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type Config from Regex
 */
#[UsesClass(Field::class)]
#[UsesClass(Matcher\String\Regex::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Regex::class)]
final class RegexTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Config $config
     */
    #[Test]
    #[DataProvider('provideConfigs')]
    public function itCreatesMatcher(
        Matcher\String\Regex $expected,
        array $config,
    ): void {
        self::assertEquals($expected, (new Regex())
            ->create($config));
    }

    /* @return \Generator<array{0: Matcher\String\Regex, 1: Config}> */
    public static function provideConfigs(): \Generator
    {
        yield 'Capitalized "name" in "path"' => [
            new Matcher\String\Regex(
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
