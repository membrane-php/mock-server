<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\Doc;
use Dagger\Container;
use Dagger\Directory;

#[DaggerObject]
#[Doc('Functions useful for development')]
class Dev
{
    public function __construct(
        private Directory $src,
    ) {}

    #[DaggerFunction]
    public function lintCheck(): Container
    {
        return (new Base(
            $this->src,
            $this->src->file('tests/fixture/api/petstore.yml'),
        ))
            ->withVendor()
            ->asContainer()
            ->withExec([
                './vendor/bin/php-cs-fixer',
                'check',
                '--diff',
                '--show-progress=none',
                '--using-cache=no',
            ]);
    }

    #[DaggerFunction]
    public function test(
        #[Doc('available testsuites from phpunit.dist.xml')]
        string $suite = 'default',
    ): Container {
        return (new Base(
            $this->src,
            $this->src->file('tests/fixture/api/petstore.yml'),
        ))
            ->withNginx()
            ->withPdo()
            ->withPcov()
            ->withVendor()
            ->asContainer()
            ->withExec(['./vendor/bin/phpunit']);
    }

}
