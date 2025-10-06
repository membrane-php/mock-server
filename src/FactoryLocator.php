<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use Psr\Container\ContainerInterface;
/**
 * @phpstan-import-type MatcherConfig from MatcherFactory
 *
 * @phpstan-type FactoryConfig array{
 *     parameters?: MatcherConfig,
 *     type: string,
 * }
 * @phpstan-type AliasesConfig array<string, class-string<MatcherFactory>>
 */
final readonly class FactoryLocator
{
    /** @param AliasesConfig $aliases */
    public function __construct(
        private ContainerInterface $container,
        private array $aliases,
    ) {}

    /** @param MatcherConfig $config */
    public function locate(array $config): MatcherFactory
    {
        $matcherType = $config['type'];

        $factoryClass = $this->aliases[$matcherType]
            ?? throw new \RuntimeException(sprintf(
                '%s must be an alias defined by your config',
                $matcherType,
            ));

        $factory = $this->container->get($factoryClass);

        if (!$factory instanceof MatcherFactory) {
            throw new \RuntimeException(sprintf(
                '%s must implement the %s interface',
                $factoryClass,
                Matcher::class,
            ));
        }

        return $factory;
    }
}
