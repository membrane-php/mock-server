<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Integration\Api\Handler;

use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Handler\Reset;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Database\Schema\MatcherTable;
use Membrane\MockServer\Database\Schema\OperationTable;
use Membrane\MockServer\Tests\Fixture\ProvidesMatchers;
use Membrane\MockServer\Tests\Integration\UsesDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(MatcherTable::class)]
#[UsesClass(OperationTable::class)]
#[UsesClass(Response::class)]
#[UsesClass(Command\Reset::class)]
#[UsesClass(Database\Model\Matcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Reset::class)]
final class ResetTest extends \PHPUnit\Framework\TestCase
{
    use UsesDatabase;

    #[Test]
    public function itResetsEmptyDb(): void
    {
        $this->getMigrator()->drop();

        $sut = new Reset(self::DB_PATH, $this->getMigrator());

        self::assertEquals(new Response(204), $sut(new Command\Reset()));
    }

    #[Test]
    public function itResetsNonEmptyDb(): void
    {
        $this->getMigrator()->drop();
        $this->getMigrator()->migrate();

        $matchers = iterator_to_array(ProvidesMatchers::generate());
        foreach ($matchers as $matcher) {
            $this->getMatcherRepository()
                ->save($matcher);
        }

        $sut = new Reset(self::DB_PATH, $this->getMigrator());

        self::assertEquals(new Response(204), $sut(new Command\Reset()));

        foreach ($matchers as $matcher) {
            self::assertNull($this->getMatcherRepository()
                ->fetchById($matcher->id));
        }
    }
}
