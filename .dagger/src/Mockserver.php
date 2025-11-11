<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Attribute\Ignore;
use Dagger\Container;
use Dagger\Directory;

use Dagger\File;
use Dagger\Service;

use function Dagger\dag;

#[DaggerObject]
#[Doc('The Membrane MockServer Dagger module')]
class Mockserver
{
    private Base $base;

    #[DaggerFunction]
    public function __construct(
        #[DefaultPath('.')]
        #[Ignore(
            '*.md',
            '.dagger/',
            '.dockerignore',
            '.git/',
            '.gitignore',
            '.idea/',
            '.cache/',
            'dagger.json',
            'generated/',
            'LICENSE',
            'storage/*.db',
            'vendor/',
        )]
        private Directory $src,
    ) {}

    #[DaggerFunction]
    public function dev(): Dev
    {
        return new Dev($this->src);
    }

    #[DaggerFunction]
    public function mockserver(File $api): Service
    {
        return (new Base($this->src, $api))
            ->withNginx()
            ->withPdo()
            ->withVendor()
            ->asService();
    }

    private function base(): Container
    {
        return dag()
            ->container()
            ->from('php:8.3-fpm-alpine')

            ->withExec(['apk', 'update'])
            ->withExec(['apk', 'add', 'nginx'])
            ->withExec(['docker-php-ext-install', 'pdo', 'pdo_mysql'])

            ->withWorkdir('/app')

            ->withFile('/etc/nginx/http.d/default.conf', $this
                ->src->file('docker/nginx.conf'))

            ->withFile('/usr/bin/composer', dag()
                ->container()
                ->from('composer/composer:2.8-bin')
                ->file('/composer'))
            ->withFile('composer.json', $this
                ->src->file('composer.json'))
            ->withFile('composer.lock', $this
                ->src->file('composer.lock'))
            ->withExec(['composer', 'install', '--no-interaction'])

            ->withDirectory('.', $this->src)

            ->withExec(['./bin/setup'])

            ->withExposedPort(8080)
            ->withExposedPort(8081);
    }
}
