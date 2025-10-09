<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api;

final class Module implements \Atto\Framework\Module\ModuleInterface
{
    /**
     * @return array<class-string, array{class?: class-string, args?: array<mixed>}>
     */
    public function getServices(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return [];
    }
}
