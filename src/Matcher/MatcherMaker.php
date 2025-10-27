<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\Attribute\ClassWithAttributes;
use Membrane\Membrane;

/**
 * @phpstan-import-type AliasesConfig from Module
 * @phpstan-import-type MatcherMakerConfig from Module
 */
final readonly class MatcherMaker
{
    /**
     * @param AliasesConfig $aliases
     */
    public function __construct(
        private Membrane $membrane,
        private array $aliases,
    ) {
    }

    /** @param MatcherMakerConfig $config */
    public function __invoke(
        array $config,
    ): Matcher {
        $spec = new ClassWithAttributes($this->aliases[$config['type']]);

        $result = $this->membrane->process($config['args'], $spec);

        if (! $result->isValid()) {
            throw new \RuntimeException('sad times');
        }

        assert($result->value instanceof Matcher);
        return $result->value;
    }

}
