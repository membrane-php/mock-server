<?php

declare(strict_types=1);

namespace Membrane\MockServer\MatcherFactory;

use Membrane\MockServer\ConfigLocator;
use Membrane\MockServer\Matcher;
use Membrane\MockServer\MatcherFactory;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-import-type MatcherAliasesConfig from ConfigLocator
 * @phpstan-import-type MatcherConfig from ConfigLocator
 *
 * @phpstan-type Config array{
 *     matcher: MatcherConfig,
 * }
 */
final readonly class Not implements \Membrane\MockServer\MatcherFactory
{
    /**
     * @param MatcherAliasesConfig $aliases
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

        $matcher = $factory->create($config['matcher']['parameters'] ?? []);

        return new Matcher\Not($matcher);
    }
}
