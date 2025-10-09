<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use Atto\Framework\Module\ModuleInterface;
use Membrane\MockServer\ConfigLocator\FromApplicationConfig;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-type Config array{
 *     aliases?: AliasesConfig,
 *     operationMap?: array<string, OperationConfig>,
 * }
 *
 * @phpstan-type AliasesConfig array<string, class-string<MatcherFactory>>
 *
 * @phpstan-type OperationConfig array{
 *      matchers?: list<array{matcher: MatcherFactoryConfig, response: ResponseConfig}>,
 *      default?: array{response: int|ResponseConfig}
 *  }
 *
 * @phpstan-type MatcherFactoryConfig array{
 *      args?: MatcherConfig,
 *      type: string,
 *  }
 * @phpstan-type MatcherConfig array<string,mixed>
 *
 * @phpstan-type ResponseConfig array{
 *      headers?: array<string, string|list<string>>,
 *      body?: ResponseBodyConfig|string,
 *      code: int,
 *  }
 * @phpstan-type ResponseBodyConfig array{
 *      content: array<mixed>,
 *      type: string,
 *  }
 */
final class Module implements ModuleInterface
{
    /**
     * @return array<class-string, array{args?: array<mixed>}>
     */
    public function getServices(): array
    {
        return [
            FromApplicationConfig::class
                => ['args' => ['config.mockServer.operationMap']],
            FactoryLocator::class
                => ['args' => [ContainerInterface::class, 'config.mockServer.aliases']],
            Handler::class
                => ['args' => [FromApplicationConfig::class, FactoryLocator::class, ResponseFactory::class]],
            MatcherFactory\AllOf::class
                => ['args' => [ContainerInterface::class, 'config.mockServer.aliases']],
            MatcherFactory\AnyOf::class
                => ['args' => [ContainerInterface::class, 'config.mockServer.aliases']],
            MatcherFactory\Array\Contains::class
                => [],
            MatcherFactory\Equals::class
                => [],
            MatcherFactory\Exists::class
                => [],
            MatcherFactory\GreaterThan::class
                => [],
            MatcherFactory\LessThan::class
                => [],
            MatcherFactory\Not::class
                => ['args' => [ContainerInterface::class, 'config.mockServer.aliases']],
            MatcherFactory\String\Regex::class
                => [],
            ResponseFactory::class
                => [],
        ];
    }

    /**
     * @return array{
     *     mockServer: Config,
     *     membrane: array{
     *         default: array{
     *             dto: array{class: class-string, useFlattener: bool},
     *             handler: class-string,
     *         },
     *     },
     * }
     */
    public function getConfig(): array
    {
        return [
            'mockServer' => [
                'aliases' => [
                    'allOf' => MatcherFactory\AllOf::class,
                    Matcher\AllOf::class => MatcherFactory\AllOf::class,

                    'anyOf' => MatcherFactory\AnyOf::class,
                    Matcher\AnyOf::class => MatcherFactory\AnyOf::class,

                    'array.contains' => MatcherFactory\Array\Contains::class,
                    Matcher\Array\Contains::class => MatcherFactory\Array\Contains::class,

                    'equals' => MatcherFactory\Equals::class,
                    Matcher\Equals::class => MatcherFactory\Equals::class,

                    'exists' => MatcherFactory\Exists::class,
                    Matcher\Exists::class => MatcherFactory\Exists::class,

                    'greater-than' => MatcherFactory\GreaterThan::class,
                    Matcher\GreaterThan::class => MatcherFactory\GreaterThan::class,

                    'less-than' => MatcherFactory\LessThan::class,
                    Matcher\LessThan::class => MatcherFactory\LessThan::class,

                    'not' => MatcherFactory\Not::class,
                    Matcher\Not::class => MatcherFactory\Not::class,

                    'string.regex' => MatcherFactory\String\Regex::class,
                    Matcher\String\Regex::class => MatcherFactory\String\Regex::class,
                ],
            ],
            'membrane' => [
                'default' => [
                    'dto' => ['class' => DTO::class, 'useFlattener' => false],
                    'handler' => \Membrane\MockServer\Handler::class,
                ],
            ],
        ];
    }
}
