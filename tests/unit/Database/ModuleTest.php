<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Database;

use Membrane\MockServer\Database\Module;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

#[\PHPUnit\Framework\Attributes\CoversClass(Module::class)]
final class ModuleTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[TestDox('The Database\\Module provides every repository interface')]
    public function itProvidesRepositories(): void
    {
        $builtIn = $this->getRepositories();
        $provided = array_keys((new Module())->getServices());
        $missing = array_diff($builtIn, $provided);

        self::assertEmpty($missing, sprintf(
            <<<ONFAILURE
            %s does not provide repositories:
            \t- %s
            ONFAILURE,
            Module::class,
            implode("\n\t- ", $missing),
        ));
    }

    #[Test]
    #[TestDox('The Database\\Module configures schemas')]
    public function itConfiguresOperationMap(): void
    {
        self::assertNotEmpty((new Module())->getConfig()['schemas']);
    }

    /** @return array<class-string> */
    private function getRepositories(): array
    {
        $reflector = new DefaultReflector(new DirectoriesSourceLocator(
            [__DIR__ . '/../../../src/Database/Repository/'],
            (new BetterReflection())->astLocator(),
        ));

        $interfaces = array_filter(
            $reflector->reflectAllClasses(),
            fn($r) => $r->isInterface(),
        );

        return array_map(fn($r) => $r->getName(), $interfaces);
    }
}
