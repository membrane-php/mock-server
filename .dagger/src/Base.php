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

    public function __construct()
    {
        $this->container = dag()
            ->container()
            ->from('php:8.3-fpm-alpine')
            ->withExposedPort(8080)
            ->withExposedPort(8081)
            ->withWorkdir('/app');
    }

    public function asContainer(): Container
    {
        return $this->container;
    }

    public function asService(): Service
    {
        return $this->container->asService(useEntrypoint: true);
    }

    public function withNginx(File $nginxConf): Base
    {
        $this->container = $this->container
            ->withExec(['apk', 'update'])
            ->withExec(['apk', 'add', 'nginx'])
            ->withFile('/etc/nginx/http.d/default.conf', $nginxConf);
        return $this;
    }

    public function withMockingApi(File $mockingApi): Base
    {
        $extension = pathinfo($mockingApi->name(), PATHINFO_EXTENSION);

        $this->container = $this->container
            ->withFile("/api/api.$extension", $mockingApi);
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

    public function withSrc(Directory $src): Base
    {
        $this->container = $this->container
            ->withDirectory('/app', $src)
            ->withExec(['/app/bin/setup'])
            ->withEntrypoint(['/app/docker/entrypoint.sh']);
        return $this;
    }

    public function withVendor(
        File $composerJson,
        File $composerLock,
        bool $noDev = false,
    ): Base {
        $this->container = $this->container
            ->withFile('/usr/bin/composer', dag()
                ->container()
                ->from('composer/composer:2.8-bin')
                ->file('/composer'))
            ->withFile('/app/composer.json', $composerJson)
            ->withFile('/app/composer.lock', $composerLock)
            ->withExec(array_filter([
                'composer',
                'install',
                '--no-interaction',
                $noDev ? '--no-dev' : '',
            ]));
        return $this;
    }
}
