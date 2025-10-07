<?php

declare(strict_types=1);

namespace Membrane\MockServer\MatcherFactory;

use Membrane\MockServer\ConfigLocator\FromApplicationConfig;
use Membrane\MockServer\FactoryLocator;
use Membrane\MockServer\Matcher;
use Membrane\MockServer\MatcherFactory;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-import-type AliasesConfig from \Membrane\MockServer\Module
 * @phpstan-import-type FactoryConfig from FactoryLocator
 *
 * @phpstan-type Config array{
 *     matcher: FactoryConfig,
 * }
 */
final readonly class Not implements \Membrane\MockServer\MatcherFactory
{
    /**
     * @param AliasesConfig $aliases
     */
    public function __construct(
        private ContainerInterface $container,
        private array $aliases,
    ) {}

    /** @param Config $config */
    public function create(array $config): Matcher
    {
        $factory = $this->container->get($this->aliases[$config['matcher']['type']]);
        assert($factory instanceof MatcherFactory);

        $matcher = $factory->create($config['matcher']['args'] ?? []);

        return new Matcher\Not($matcher);
    }
}
