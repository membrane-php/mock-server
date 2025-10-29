<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\MatcherFactory;

use Membrane\MockServer\Matcher\Matcher;
use Membrane\MockServer\Matcher\MatcherFactory;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-import-type AliasesConfig from \Membrane\MockServer\Mocking\Module
 * @phpstan-import-type MatcherFactoryConfig from \Membrane\MockServer\Mocking\Module
 *
 * @phpstan-type Config array{matchers: list<MatcherFactoryConfig>}
 */
final readonly class AllOf implements \Membrane\MockServer\Matcher\MatcherFactory
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

        return new \Membrane\MockServer\Matcher\Matcher\AllOf(...$matchers);
    }
}
