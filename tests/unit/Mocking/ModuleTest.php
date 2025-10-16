<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking;

use Membrane\MockServer\Mocking\MatcherFactory;
use Membrane\MockServer\Mocking\Module;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

#[\PHPUnit\Framework\Attributes\CoversClass(Module::class)]
final class ModuleTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[TestDox('The Mocking\\Module provides factories for built-in matchers')]
    public function itProvidesMatchers(): void
    {
        $builtIn = $this->getBuiltInMatcherFactories();
        $provided = array_keys((new Module())->getServices());
        $missing = array_diff($builtIn, $provided);

        self::assertEmpty($missing, sprintf(
            <<<ONFAILURE
            %s does not provide built-in matchers:
            \t- %s
            ONFAILURE,
            Module::class,
            implode("\n\t- ", $missing),
        ));
    }

    #[Test]
    #[TestDox('The Mocking\\Module configures aliases for built-in matchers')]
    public function itConfiguresMatcherAliases(): void
    {
        $builtIn = $this
            ->getBuiltInMatcherFactories();

        $provided = array_values((new Module())
            ->getConfig()['mockServer']['aliases']);

        $missing = array_diff($builtIn, $provided);

        self::assertEmpty($missing, sprintf(
            <<<ONFAILURE
            %s has no aliases for built-in matchers:
            \t- %s
            ONFAILURE,
            Module::class,
            implode("\n\t- ", $missing),
        ));
    }

    /** @return array<class-string<MatcherFactory>> */
    private function getBuiltInMatcherFactories(): array
    {
        $reflector = new DefaultReflector(new DirectoriesSourceLocator(
            [__DIR__ . '/../../../src/Mocking/MatcherFactory/'],
            (new BetterReflection())->astLocator(),
        ));

        $factories = array_filter(array_map(
            fn($r) => new \ReflectionClass($r->getName()),
            $reflector->reflectAllClasses(),
        ), fn($r) => $r->implementsInterface(MatcherFactory::class));

        return array_map(fn($f) => $f->getName(), $factories);
    }
}
