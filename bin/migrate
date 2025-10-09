#!/usr/bin/env php
<?php

declare(strict_types=1);

use Atto\Db\Migrator;
use Atto\Framework\Application\DefaultApplication;
use Atto\Framework\Module\Atto;
use Laminas\Stdlib\ArrayUtils;

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Composer autoloading
include __DIR__ . '/../vendor/autoload.php';

// Retrieve configuration
$appConfig = require __DIR__ . '/../config/application.config.php';
if (file_exists(__DIR__ . '/../config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__ . '/../config/development.config.php');
}

$appConfig = array_merge([
    'debug' => false,
    'env' => Atto::ENV_PROD,
    'application' => DefaultApplication::class,
], $appConfig);

// Run the application!
/**  @var Migrator $migrator */
$migrator = Atto::buildContainer($appConfig)->get(Migrator::class);
echo $migrator->migrateToString();

$migrator->migrate();
