<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Integration\Database\Repository\Matcher;

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
final class SqlTest extends \PHPUnit\Framework\TestCase
{
    use UsesDatabase;

    #[Test]
    public function itRemovesMatchers(): void
    {
        $this->getMigrator()->drop();
        $this->getMigrator()->migrate();

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
    #[DataProvider('provideMatchersToFetchById')]
    public function itFetchesById(
        ?Model\Matcher $expected,
        array $matchers,
        string $id,
    ): void {
        $this->getMigrator()->drop();
        $this->getMigrator()->migrate();

        $sut = $this->getMatcherRepository();

        foreach ($matchers as $matcher) {
            $sut->save($matcher);
        }

        self::assertEquals($expected, $sut->fetchById($id));
    }

    /**
     * @param Model\Matcher[] $expected
     * @param Model\Matcher[] $matchers
     */
    #[Test]
    #[DataProvider('provideMatchersToFetchByOperationId')]
    public function itFetchesByOperationId(
        array $expected,
        array $matchers,
        string $operationId,
    ): void {
        $this->getMigrator()->drop();
        $this->getMigrator()->migrate();

        $sut = $this->getMatcherRepository();

        foreach ($matchers as $matcher) {
            $sut->save($matcher);
        }

        self::assertEquals($expected, $sut->fetchByOperationId($operationId));
    }

    /**
     * @return \Generator<array{
     *     0: ?Model\Matcher,
     *     1: Model\Matcher[],
     *     2: string,
     *  }>
     */
    public static function provideMatchersToFetchById(): \Generator
    {
        yield 'no matchers' => [null, [], 'abc123'];
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

    /**
     * @return \Generator<array{
     *     0: Model\Matcher[],
     *     1: Model\Matcher[],
     *     2: string,
     *  }>
     */
    public static function provideMatchersToFetchByOperationId(): \Generator
    {
        yield 'no matchers' => [[], [], 'list-pets'];
        yield 'non-matching matchers' => [
            [],
            iterator_to_array(Fixture\ProvidesMatchers::generate()),
            'howdy-planet'
        ];
        yield 'matching matchers' => (function () {
            $matchers = Fixture\ProvidesMatchers::generate();

            $expected = $matchers->current();

            return [
                [$expected],
                iterator_to_array($matchers),
                $expected->operationId,
            ];
        })();
    }
}
