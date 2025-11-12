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
            ->asService($api);
    }
}
