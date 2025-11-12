<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Attribute\Ignore;
use Dagger\Directory;
use Dagger\File;
use Dagger\Service;

#[DaggerObject]
#[Doc('The Membrane MockServer Dagger module')]
class Mockserver
{
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
        return (new Base())
            ->withPdo()
            ->withNginx($this->src->file('docker/nginx.conf'))
            ->withVendor(
                $this->src->file('composer.json'),
                $this->src->file('composer.lock'),
            )
            ->withMockingApi($api)
            ->withSrc($this->src)
            ->asService();
    }
}
