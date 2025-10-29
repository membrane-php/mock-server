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
        \Membrane\MockServer\Matcher\Module::class,
    ],
    'globalConfig' => [
        'membrane' => [
            'openAPISpec' => __DIR__ . '/../api/api.yml',
            'routes_file' => __DIR__ . '/../generated/api/routes.php',
            'cached_builders' => [
                \Membrane\MockServer\Generated\Api\CachedRequestBuilder::class,
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
