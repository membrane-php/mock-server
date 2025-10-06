<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use Psr\Container\ContainerInterface;

final readonly class MatcherFactory
{
    public function __construct(
        private ContainerInterface $container,
        private array $aliases,
    ) {}

    public function __invoke(array $matcherConfig): Matcher
    {
        $matcherType = $matcherConfig['type'];

        $factoryClass = $this->aliases[$matcherType]
            ?? throw new \RuntimeException(sprintf(
                '%s must be an alias defined by your config',
                $matcherType,
            ));

        $factory = $this->container->get($factoryClass);

        if (! class_implements(Matcher::class)) {


            throw new \RuntimeException(sprintf(
                '%s must implement the %s interface',
                $matcherClass,
                Matcher::class,
            ));
        }
    }
}
