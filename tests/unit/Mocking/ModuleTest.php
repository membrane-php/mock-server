<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Mocking;

use Membrane\MockServer\Mocking\ConfigLocator\FromMultipleSources;
use Membrane\MockServer\Mocking\Module;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\CoversClass(Module::class)]
final class ModuleTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function itProvidesHandler(): void
    {
        $sut = new Module();

        $handler = $sut->getConfig()['membrane']['default']['handler'];

        self::assertArrayHasKey($handler, $sut->getServices());
    }

    #[Test]
    public function itLocatesConfigFromMultipleSources(): void
    {
        $services = (new Module())->getServices();

        self::assertArrayHasKey(FromMultipleSources::class, $services);

        $args = $services[FromMultipleSources::class]['args'];

        foreach ($args as $arg) {
            self::assertArrayHasKey($arg, $services);
        }
    }
}
