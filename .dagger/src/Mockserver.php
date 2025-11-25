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
    #[DaggerFunction]
    public function __construct(
        #[DefaultPath('.')]
        #[Ignore(
            '*.md',
            '.cache/',
            '.dagger/',
            '.dockerignore',
            '.gitignore',
            '.idea/',
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
    public function mockserver(File $api): Container
    {
        return (new Base())
            ->withPdo()
            ->withNginx($this->src->file('docker/nginx.conf'))
            ->withVendor(
                $this->src->file('composer.json'),
                $this->src->file('composer.lock'),
                noDev: true,
            )
            ->withMockingApi($api)
            ->withSrc($this->src)
            ->asContainer();
    }

    #[DaggerFunction]
    public function makeTag(): string
    {
        return dag()
            ->container()
            ->from('alpine/git')
            ->withMountedDirectory('/app', $this->src)
            ->withWorkdir('/app')
            ->withExec(['git', 'describe', '--tags'])
            ->stdout();
    }

    #[DaggerFunction]
    #[Doc('Publish a container image to a private registry')]
    public function publish(
        #[Doc('registry address')]
        string $registry,
    ): string {
        $mockserver = (new Base())
            ->withPdo()
            ->withNginx($this->src->file('docker/nginx.conf'))
            ->withVendor(
                $this->src->file('composer.json'),
                $this->src->file('composer.lock'),
                noDev: true,
            )
            ->withSrc($this->src)
            ->asContainer()
            ->withEnvVariable('MEMBRANE_MOCKSERVER_DEBUG', 'false');

        $tag = $this->makeTag();

        return $mockserver
            ->publish("$registry/membrane-php/mock-server:$tag");
    }
}
