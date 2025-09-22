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
            'mockServer' => [
                'get-weave-action-actionId' => [
                    'matchers' => [
                        [
                            'matcher' => new Matcher\Equals(new Field('actionId', 'request', 'path'), 1),
                            'response' => new Response(200, [], json_encode(['action' => 'move']))
                        ],
                    ],
                    'defaultResponse' => new Response(200),
                ]
            ],
            'membrane' => ['default' => [
                'dto' => \Membrane\MockServer\DTO::class,
                'handler' => \Membrane\MockServer\Handler::class,
            ]],
        ];
    }
}
