<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Container;
use Dagger\Directory;
use Dagger\File;
use Dagger\Service;

use function Dagger\dag;

/**
 * @internal Called by Dagger Objects,
 *           but not exposed to end-user.
 */
class Base
{
    private Container $container;

    public function __construct(
        private Directory $src,

    ) {
        $this->container = dag()
            ->container()
            ->from('php:8.3-fpm-alpine')
            ->withExposedPort(8080)
            ->withExposedPort(8081)
            ->withWorkdir('/app');
    }

    public function asContainer(File $mockingApi): Container
    {
        $extension = pathinfo($mockingApi->name(), PATHINFO_EXTENSION);

        return $this
            ->container
            ->withDirectory('/app', $this->src)
            ->withFile("/api/api.$extension", $mockingApi)
            ->withExec(['/app/bin/setup']);
    }

    public function asService(File $mockingApi): Service
    {
        return $this->asContainer($mockingApi)
            ->withEntrypoint(['/app/docker/entrypoint.sh'])
            ->asService(useEntrypoint: true);
    }

    public function withNginx(): Base
    {
        $this->container = $this->container
            ->withExec(['apk', 'update'])
            ->withExec(['apk', 'add', 'nginx'])
            ->withFile('/etc/nginx/http.d/default.conf', $this
                ->src->file('docker/nginx.conf'));
        return $this;
    }

    public function withPdo(): Base
    {
        $this->container = $this->container
            ->withExec(['docker-php-ext-install', 'pdo', 'pdo_mysql']);
        return $this;
    }

    public function withPcov(): Base
    {
        $this->container = $this->container
            ->withFile('/usr/local/bin/php-ext-install', dag()
                ->container()
                ->from('ghcr.io/mlocati/php-extension-installer')
                ->file('/usr/bin/install-php-extensions'));

        $this->container = $this->container
            ->withExec(['php-ext-install', 'pcov']);

        return $this;
    }

    public function withVendor(): Base
    {
        $this->container = $this->container
            ->withFile('/usr/bin/composer', dag()
                ->container()
                ->from('composer/composer:2.8-bin')
                ->file('/composer'))
            ->withFile('/app/composer.json', $this
                ->src->file('composer.json'))
            ->withFile('/app/composer.lock', $this
                ->src->file('composer.lock'))
            ->withExec(['composer', 'install', '--no-interaction']);
        return $this;
    }
}
