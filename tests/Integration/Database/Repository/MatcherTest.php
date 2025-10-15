<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Integration\Database\Repository;

use Membrane\MockServer\Database\Model;
use Membrane\MockServer\Database\Repository\Matcher;
use Membrane\MockServer\Database\Schema\MatcherTable;
use Membrane\MockServer\Database\Schema\OperationTable;
use Membrane\MockServer\Tests\Fixture;
use Membrane\MockServer\Tests\Integration\UsesDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[UsesClass(MatcherTable::class)]
#[UsesClass(OperationTable::class)]
#[UsesClass(Model\Matcher::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Matcher\Sql::class)]
final class MatcherTest extends \PHPUnit\Framework\TestCase
{
    use UsesDatabase;

    #[Test]
    public function itRemovesMatchers(): void
    {
        $this->resetDb();
        $matcher = Fixture\ProvidesMatchers::generate()->current();
        $sut = $this->getMatcherRepository();

        $sut->save($matcher);
        self::assertNotNull($sut->fetchById($matcher->id));

        $sut->remove($matcher);
        self::assertNull($sut->fetchById($matcher->id));
    }

    /**
     * @param Model\Matcher[] $matchers
     */
    #[Test]
    #[DataProvider('provideMatchersToFetch')]
    public function itFetchesById(
        ?Model\Matcher $expected,
        array $matchers,
        string $id,
    ): void {
        $this->resetDb();

        $sut = $this->getMatcherRepository();

        foreach ($matchers as $matcher) {
            $sut->save($matcher);
        }

        self::assertEquals($expected, $sut->fetchById($id));
    }

    /**
     * @return \Generator<array{
     *     0: ?Model\Matcher,
     *     1: Model\Matcher[],
     *     2: string,
     *  }>
     */
    public static function provideMatchersToFetch(): \Generator
    {
        yield 'no matchers' => [null, [], 'list-pets'];
        yield 'non-matching matchers' => [
            null,
            iterator_to_array(Fixture\ProvidesMatchers::generate()),
            'zxy098'
        ];
        yield 'matching matcher' => (function () {
            $matchers = Fixture\ProvidesMatchers::generate();

            $expected = $matchers->current();

            return [
                $expected,
                iterator_to_array($matchers),
                $expected->id,
            ];
        })();
    }
}
