<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Matcher;

use Membrane\Attribute\Builder;
use Membrane\Membrane;
use Membrane\MockServer\Matcher\ConfigValidator;
use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Matcher\Module;
use Membrane\MockServer\Mocking\Field;
use Membrane\Result\FieldName;
use Membrane\Result\Message;
use Membrane\Result\MessageSet;
use Membrane\Result\Result;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @phpstan-import-type AliasesConfig from Module
 * @phpstan-import-type MatcherFactoryConfig from Module
 */
#[UsesClass(Field::class)]
#[UsesClass(Matcher\Equals::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(ConfigValidator::class)]
final class ConfigValidatorTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @param AliasesConfig $aliases
     * @param MatcherFactoryConfig $config
     */
    #[Test]
    #[DataProvider('provideConfigsToValidate')]
    public function itValidatesConfig(
        Result $expected,
        array $aliases,
        array $config,
    ): void{
        $sut = new ConfigValidator(
            new Membrane(new Builder()),
            $aliases,
        );

        self::assertEquals($expected, $sut->validate($config));
    }

    /**
     * @return \Generator<array{
     *     0: Result,
     *     1: array<string, class-string>,
     *     2: MatcherFactoryConfig
     * }>
     */
    public static function provideConfigsToValidate(): \Generator
    {
        yield 'valid equals config' => [
            Result::noResult(new Matcher\Equals(new Field('id', 'path'), 5)),
            ['equals' => Matcher\Equals::class],
            [
                'type' => 'equals',
                'args' => [
                    'field' => ['path', 'id'],
                    'value' => 5,
                ],
            ],
        ];

        yield 'equals config missing field' => (function () {
            $config = [
                'type' => 'equals',
                'args' => ['value' => 5],
            ];

            return [
                Result::invalid($config['args'], new MessageSet(
                    new FieldName('', '', ''),
                    new Message(sprintf(
                        '%s::__construct(): Argument #1 ($field) not passed',
                        Matcher\Equals::class,
                    ), []),
                )),
                ['equals' => Matcher\Equals::class],
                $config,
            ];
        })();

        yield 'equals config with invalid field argument' => (function () {
            $config = [
                'type' => 'equals',
                'args' => ['field' => 123, 'value' => 5],
            ];

            return [
                Result::invalid($config['args'], new MessageSet(
                    new FieldName('', '', '', 'field'),
                    new Message('Value passed to FromArray filter must be an array, %s passed instead', ['integer']),
                )),
                ['equals' => Matcher\Equals::class],
                $config,
            ];
        })();

        yield 'allOf config without any submatchers' => (function () {
            $config = [
                'type' => 'allOf',
                'args' => ['submatchers' => []],
            ];

            return [
                Result::invalid($config['args'], new MessageSet(
                    new FieldName('', 'submatchers', 'args', 'submatchers'),
                    new Message('Array is expected have a minimum of %d values', ['1']),
                )),
                ['allOf' => Matcher\AllOf::class],
                $config,
            ];
        })();
    }
}
