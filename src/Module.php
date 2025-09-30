<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use Atto\Framework\Module\ModuleInterface;
use GuzzleHttp\Psr7\Response;
use Membrane\MockServer\Matcher\AllOf;

final class Module implements ModuleInterface
{

    /**
     * @return array[]
     */
    public function getServices(): array
    {
        return [
            Handler::class => ['args' => [
                'config.mockServer',
            ]],
        ];
    }

    public function getConfig(): array
    {
        return [
            'membrane' => ['default' => [
                'dto' => [
                    'class' => \Membrane\MockServer\DTO::class,
                    'useFlattener' => false,
                ],
                'handler' => \Membrane\MockServer\Handler::class,
            ]],
        ];
    }
}
