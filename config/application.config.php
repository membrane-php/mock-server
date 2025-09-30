<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Membrane\MockServer\Field;
use Membrane\MockServer\Matcher;

return [
    'modules' => [
        \Atto\Framework\Module::class,
        \Atto\Membrane\Module::class,
        \Atto\Psr7\Module::class,
        \Membrane\MockServer\Module::class,
    ],
    'globalConfig' => [
        'mockServer' => [
            'get-weave-action-actionId' => [
                'matchers' => [
                    [
                        'matcher' => new Matcher\Equals(
                            new Field('actionId', 'path'),
                            '1',
                        ),
                        'response' => new Response(202, [], json_encode(['action' => 'move'])),
                    ],
                ],
                'default' => ['response' => new Response(203)]
            ]
        ],
        'membrane' => [
            'openAPISpec' => __DIR__ . '/../api/openapi.json',
            'routes_file' => __DIR__ . '/../generated/routes.php',
            'cached_builders' => [
                \Membrane\MockServer\Generated\CachedRequestBuilder::class,
            ],
        ],
    ],
    'debug' => true,
    'application' => \Atto\Membrane\Application\MembraneOpenApi::class
];
