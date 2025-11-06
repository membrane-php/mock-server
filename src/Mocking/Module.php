<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking;

use Membrane\MockServer\Database;
use Membrane\MockServer\Matcher\FactoryLocator;
use Membrane\MockServer\Matcher\MatcherFactory;
use Membrane\MockServer\Mocking\ConfigLocator\FromApplicationConfig;
use Membrane\MockServer\Mocking\ConfigLocator\FromDatabase;
use Membrane\MockServer\Mocking\ConfigLocator\FromMultipleSources;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-type Config array{
 *     operationMap?: array<string, OperationConfig>,
 * }
 *
 * @phpstan-type AliasesConfig array<string, class-string<MatcherFactory>>
 *
 * @phpstan-type OperationConfig array{
 *     matchers?: list<array{matcher: MatcherFactoryConfig, response: ResponseConfig|int}>,
 *     default?: array{response: ResponseConfig|int}
 * }
 *
 * @phpstan-import-type  MatcherFactoryConfig from \Membrane\MockServer\Matcher\Module
 *
 * @phpstan-type ResponseConfig array{
 *     headers?: array<string, string|list<string>>,
 *     body?: mixed[]|string,
 *     code: int,
 * }
 */
final class Module implements \Atto\Framework\Module\ModuleInterface
{
    /**
     * @return array<class-string, array{class?: class-string, args?: array<mixed>}>
     */
    public function getServices(): array
    {
        return [
            FactoryLocator::class
            => ['args' => [ContainerInterface::class, 'config.mockServer.matcher.aliases']],
            FromMultipleSources::class
                => ['args' => [
                    FromDatabase::class,
                    FromApplicationConfig::class,
                ]],
            FromDatabase::class
                => ['args' => [
                    Database\Repository\Operation::class,
                    Database\Repository\Matcher::class,
                ]],
            FromApplicationConfig::class
                => ['args' => ['config.mockServer.operationMap']],
            Handler::class
                => ['args' => [FromMultipleSources::class, FactoryLocator::class, ResponseFactory::class]],
            ResponseFactory::class
                => [],
        ];
    }

    /**
     * @return array{
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
            'membrane' => [
                'default' => [
                    'dto' => ['class' => DTO::class, 'useFlattener' => false],
                    'handler' => Handler::class,
                ],
            ],
        ];
    }
}
