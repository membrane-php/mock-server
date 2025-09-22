#!/usr/bin/env php
<?php

$autoloadFiles = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
];

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
        break;
    }
}

use Membrane\Console\Command\CacheOpenAPIProcessors;
use Membrane\OpenAPIRouter\Console\Command\CacheOpenAPIRoutes;
use Symfony\Component\Console\Application;

$application = new Application();

if (class_exists(CacheOpenAPIProcessors::class)) {
    $application->add(new CacheOpenAPIProcessors());
}

if (class_exists(CacheOpenAPIRoutes::class)) {
    $application->add(new CacheOpenAPIRoutes());
}

$application->run();
