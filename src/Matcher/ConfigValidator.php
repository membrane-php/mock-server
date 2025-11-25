<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use League\Container\Container;
use Membrane\Attribute\Builder;
use Membrane\Attribute\ClassWithAttributes;
use Membrane\Membrane;
use Membrane\MockServer\Matcher\Matcher\AllOf;
use Membrane\MockServer\Matcher\Matcher\AnyOf;
use Membrane\MockServer\Matcher\Matcher\Not;
use Membrane\Processor\BeforeSet;
use Membrane\Processor\Collection;
use Membrane\Processor\Field;
use Membrane\Processor\FieldSet;
use Membrane\Result\FieldName;
use Membrane\Result\Result;
use Membrane\Validator\Collection\Contained;
use Membrane\Validator\Collection\Count;
use Membrane\Validator\FieldSet\RequiredFields;
use Membrane\Validator\Type\IsString;

/**
 * @phpstan-import-type AliasesConfig from Module
 */
final readonly class ConfigValidator
{
    /** @param AliasesConfig $aliases */
    public function __construct(
        private Membrane $membrane,
        private array $aliases,
    ) {}

    /**
     * @param array<mixed> $config
     *        A valid array matches the MatcherFactoryConfig from Matcher Module
     */
    public function validate(array $config): Result
    {
        $result = new FieldSet(
            'matcher-config',
            new BeforeSet(new RequiredFields('type', 'args')),
            new Field('type', new IsString(), new Contained(array_keys($this->aliases))),
        )->process(new FieldName('config'), $config);

        if ($result->isValid()) {
            assert(isset($config['type'])
                && isset($config['args']));
        } else {
            return $result;
        }

        $type = $this->aliases[$config['type']];

        if (in_array($type, [AllOf::class, AnyOf::class, Not::class])) {
            $result = new FieldSet(
                'args',
                new BeforeSet(new RequiredFields('submatchers')),
                new Collection('submatchers', new BeforeSet(new Count(1))),
            )->process(new FieldName('submatchers'), $config['args']);

            foreach ((array) $config['args'] as $subMatcher) {
                $result->merge($this->validate($subMatcher));
            }

            return $result;
        }

        return $this->membrane
            ->process($config['args'], new ClassWithAttributes($type));
    }
}
