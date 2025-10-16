<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api;

use Membrane\MockServer\Api\Module;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

#[\PHPUnit\Framework\Attributes\CoversClass(Module::class)]
final class ModuleTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[TestDox('The Api\\Module provides built-in handlers')]
    public function itProvidesHandlers(): void
    {
        $builtIn = $this->getBuiltInHandlers();
        $provided = array_keys((new Module())->getServices());
        $missing = array_diff($builtIn, $provided);

        self::assertEmpty($missing, sprintf( //@TODO fix test
            <<<ONFAILURE
            %s does not provide built-in handlers:
            \t- %s
            ONFAILURE,
            Module::class,
            implode("\n\t- ", $missing),
        ));
    }

    #[Test]
    #[TestDox('The Api\\Module configures operationMap for all handlers')]
    public function itConfiguresOperationMap(): void
    {
        $builtIn = $this->getBuiltInHandlers();

        $provided = array_map(fn($o) => $o['handler'], array_values((new Module())
            ->getConfig()['membrane']['operationMap']));

        $missing = array_diff($builtIn, $provided);

        self::assertEmpty($missing, sprintf(
            <<<ONFAILURE
            %s has no operation for built-in handlers:
            \t- %s
            ONFAILURE,
            Module::class,
            implode("\n\t- ", $missing),
        ));
    }

    /** @return array<class-string> */
    private function getBuiltInHandlers(): array
    {
        $reflector = new DefaultReflector(new DirectoriesSourceLocator(
            [__DIR__ . '/../../../src/Api/Handler/'],
            (new BetterReflection())->astLocator(),
        ));

        return array_map(fn($r) => $r->getName(), $reflector->reflectAllClasses());
    }
}
