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
 *     matchers: list<FactoryConfig>,
 * }
 */
final readonly class AllOf implements \Membrane\MockServer\MatcherFactory
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
        $matchers = [];

        foreach ($config['matchers'] as $matcher) {
            $factory = $this->container->get($this->aliases[$matcher['type']]);
            assert($factory instanceof MatcherFactory);

            $matchers []= $factory->create($matcher['args'] ?? []);
        }

        return new Matcher\AllOf(...$matchers);
    }
}
