<?php

declare(strict_types=1);

return [
    'modules' => [
        \Atto\Framework\Module::class,
        \Atto\Db\Module::class,
        \Atto\Membrane\Module::class,
        \Atto\Psr7\Module::class,
        \Membrane\MockServer\Api\Module::class,
        \Membrane\MockServer\Database\Module::class,
    ],
    'globalConfig' => [
        'mockServer' => [
            'operationMap' => [
                'get-weave-action-actionId' => [
                    'matchers' => [
                        [
                            'matcher' => [
                                'type' => 'equals',
                                'args' => [
                                    'field' => ['path', 'actionId'],
                                    'value' => 2,
                                ],
                            ],
                            'response' => 200,
                        ],
                    ],
                    'default' => ['response' => 203],
                ],
            ],
        ],
        'membrane' => [
            'openAPISpec' => __DIR__ . '/../api/openapi.json',
            'routes_file' => __DIR__ . '/../generated/routes.php',
            'cached_builders' => [
                \Membrane\MockServer\Generated\CachedRequestBuilder::class,
            ],
        ],
        'database' => [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../storage/app.db',
        ],
    ],
    'debug' => true,
    'application' => \Atto\Membrane\Application\MembraneOpenApi::class,
];
