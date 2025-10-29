<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\Mocking\ConfigLocator\FromApplicationConfig;
use Membrane\MockServer\Mocking\ConfigLocator\FromMultipleSources;
use Membrane\MockServer\Mocking\Handler;
use Membrane\MockServer\Mocking\ResponseFactory;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-type Config array{
 *     aliases?: AliasesConfig,
 * }
 *
 * @phpstan-type AliasesConfig array<string, class-string<MatcherFactory>>
 *
 * @phpstan-type MatcherFactoryConfig array{
 *      args?: MatcherConfig,
 *      type: string,
 *  }
 * @phpstan-type MatcherConfig array<string,mixed>
 *
 * @phpstan-type ResponseConfig array{
 *      headers?: array<string, string|list<string>>,
 *      body?: mixed[]|string,
 *      code: int,
 *  }
 */
final class Module implements \Atto\Framework\Module\ModuleInterface
{
    /**
     * @return array<class-string, array{class?: class-string, args?: array<mixed>}>
     */
    public function getServices(): array
    {
        return [
            ConfigValidator::class
                => ['args' => [ContainerInterface::class, 'config.mockServer.matcher.aliases']],
            \Membrane\MockServer\Matcher\MatcherFactory\AllOf::class
                => ['args' => [ContainerInterface::class, 'config.mockServer.matcher.aliases']],
            \Membrane\MockServer\Matcher\MatcherFactory\AnyOf::class
                => ['args' => [ContainerInterface::class, 'config.mockServer.matcher.aliases']],
            \Membrane\MockServer\Matcher\MatcherFactory\Array\Contains::class
                => [],
            \Membrane\MockServer\Matcher\MatcherFactory\Equals::class
                => [],
            \Membrane\MockServer\Matcher\MatcherFactory\Exists::class
                => [],
            \Membrane\MockServer\Matcher\MatcherFactory\GreaterThan::class
                => [],
            \Membrane\MockServer\Matcher\MatcherFactory\LessThan::class
                => [],
            \Membrane\MockServer\Matcher\MatcherFactory\Not::class
                => ['args' => [ContainerInterface::class, 'config.mockServer.matcher.aliases']],
            \Membrane\MockServer\Matcher\MatcherFactory\String\Regex::class
                => [],
            ResponseFactory::class
                => [],
        ];
    }

    /**
     * @return array{ mockServer: array{ matcher: Config } }
     */
    public function getConfig(): array
    {
        return [
            'mockServer' => [
                'matcher' => [
                    'aliases' => [
                        'allOf' => \Membrane\MockServer\Matcher\MatcherFactory\AllOf::class,
                        \Membrane\MockServer\Matcher\Matcher\AllOf::class => \Membrane\MockServer\Matcher\MatcherFactory\AllOf::class,

                        'anyOf' => \Membrane\MockServer\Matcher\MatcherFactory\AnyOf::class,
                        \Membrane\MockServer\Matcher\Matcher\AnyOf::class => \Membrane\MockServer\Matcher\MatcherFactory\AnyOf::class,

                        'array.contains' => \Membrane\MockServer\Matcher\MatcherFactory\Array\Contains::class,
                        \Membrane\MockServer\Matcher\Matcher\Array\Contains::class => \Membrane\MockServer\Matcher\MatcherFactory\Array\Contains::class,

                        'equals' => \Membrane\MockServer\Matcher\MatcherFactory\Equals::class,
                        \Membrane\MockServer\Matcher\Matcher\Equals::class => \Membrane\MockServer\Matcher\MatcherFactory\Equals::class,

                        'exists' => \Membrane\MockServer\Matcher\MatcherFactory\Exists::class,
                        \Membrane\MockServer\Matcher\Matcher\Exists::class => \Membrane\MockServer\Matcher\MatcherFactory\Exists::class,

                        'greater-than' => \Membrane\MockServer\Matcher\MatcherFactory\GreaterThan::class,
                        \Membrane\MockServer\Matcher\Matcher\GreaterThan::class => \Membrane\MockServer\Matcher\MatcherFactory\GreaterThan::class,

                        'less-than' => \Membrane\MockServer\Matcher\MatcherFactory\LessThan::class,
                        \Membrane\MockServer\Matcher\Matcher\LessThan::class => \Membrane\MockServer\Matcher\MatcherFactory\LessThan::class,

                        'not' => \Membrane\MockServer\Matcher\MatcherFactory\Not::class,
                        \Membrane\MockServer\Matcher\Matcher\Not::class => \Membrane\MockServer\Matcher\MatcherFactory\Not::class,

                        'string.regex' => \Membrane\MockServer\Matcher\MatcherFactory\String\Regex::class,
                        \Membrane\MockServer\Matcher\Matcher\String\Regex::class => \Membrane\MockServer\Matcher\MatcherFactory\String\Regex::class,
                    ],
                ],
            ],
        ];
    }
}
