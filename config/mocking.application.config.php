<?php

declare(strict_types=1);

$api = current(glob('/api/api.*') ?: [])
    ?: throw new \RuntimeException('api not found');

$debug = filter_var(getenv('MEMBRANE_MOCKSERVER_DEBUG'), FILTER_VALIDATE_BOOL);

$userConfig = file_exists('/config/config.php') ? require_once('/config/config.php') : [];

return [
    'modules' => [
        \Atto\Framework\Module::class,
        \Atto\Db\Module::class,
        \Atto\Membrane\Module::class,
        \Atto\Psr7\Module::class,
        \Membrane\MockServer\Database\Module::class,
        \Membrane\MockServer\Matcher\Module::class,
        \Membrane\MockServer\Mocking\Module::class,
    ],
    'globalConfig' => [
        'mockServer' => [
            'operationMap' => $userConfig,
        ],
        'membrane' => [
            'openAPISpec' => $api,
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
    'debug' => $debug,
    'application' => \Atto\Membrane\Application\MembraneOpenApi::class,
];
