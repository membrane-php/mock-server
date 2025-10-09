<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\MatcherFactory;

use Membrane\MockServer\Mocking\Matcher;
use Membrane\MockServer\Mocking\MatcherFactory;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-import-type AliasesConfig from \Membrane\MockServer\Mocking\Module
 * @phpstan-import-type MatcherFactoryConfig from \Membrane\MockServer\Mocking\Module
 *
 * @phpstan-type Config array{matchers: list<MatcherFactoryConfig>}
 */
final readonly class AllOf implements \Membrane\MockServer\Mocking\MatcherFactory
{
    /** @param AliasesConfig $aliases */
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

            $matchers [] = $factory->create($matcher['args'] ?? []);
        }

        return new Matcher\AllOf(...$matchers);
    }
}
