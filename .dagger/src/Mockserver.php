<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Attribute\Ignore;
use Dagger\Attribute\ReturnsListOfType;
use Dagger\Container;
use Dagger\Directory;

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
}
