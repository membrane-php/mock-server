<?php

declare(strict_types=1);

$api = glob('/api/api.*');

return [
    'modules' => [
        \Atto\Framework\Module::class,
        \Atto\Db\Module::class,
        \Atto\Membrane\Module::class,
        \Atto\Psr7\Module::class,
        \Membrane\MockServer\Database\Module::class,
        \Membrane\MockServer\Mocking\Module::class,
    ],
    'globalConfig' => [
        'mockServer' => [
            'operationMap' => [
                // operationId keys mapping to operation config arrays
            ],
        ],
        'membrane' => [
            'openAPISpec' => file_get_contents(__DIR__ . '/openapi-file'),
            'routes_file' => __DIR__ . '/../generated/mocking/routes.php',
            'cached_builders' => [
                \Membrane\MockServer\Generated\Mocking\CachedRequestBuilder::class,
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
