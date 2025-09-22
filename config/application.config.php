<?php

declare(strict_types=1);

return [
    'modules' => [
        \Atto\Framework\Module::class,
        \Atto\Membrane\Module::class,
        \Membrane\MockServer\Module::class,
    ],
    'globalConfig' => [
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
