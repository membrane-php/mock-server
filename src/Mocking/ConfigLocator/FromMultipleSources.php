<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\ConfigLocator;

use Membrane\MockServer\Mocking\ConfigLocator;

/**
 * @phpstan-import-type Config from ConfigLocator
 */
final readonly class FromMultipleSources implements ConfigLocator
{
    /** @var ConfigLocator[] */
    private array $configLocators;

    public function __construct(
        ConfigLocator $configLocator,
        ConfigLocator ...$configLocators,
    ) {
        $this->configLocators = [$configLocator, ...$configLocators];
    }

    /** @return ?Config */
    public function getOperationConfig(string $operationId): ?array
    {
        foreach ($this->configLocators as $configLocator) {
            $config = $configLocator->getOperationConfig($operationId);
            if ($config !== null) {
                return $config;
            }
        }

        return null;
    }
}
