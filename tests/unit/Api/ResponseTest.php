<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Unit\Api;

use Membrane\MockServer\Api\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\CoversClass(Response::class)]
final class ResponseTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideStatusCodes')]
    public function itGetsStatusCode(
        int $code,
    ): void {
        self::assertSame($code, (new Response($code))->getStatusCode());
    }

    /** @return array<array{0:int}> */
    public static function provideStatusCodes(): array
    {
        return [[200], [201], [404]];
    }

    #[Test]
    #[DataProvider('provideModels')]
    public function itIsJsonSerializable(
        ?\JsonSerializable $model,
    ): void {
        self::assertEquals($model, (new Response(200, $model))->jsonSerialize());
    }

    /** @return \Generator<array{0:?\JsonSerializable}> */
    public static function provideModels(): \Generator
    {
        yield 'null' => [null];

        yield 'anonymous class' => [
            new class implements \JsonSerializable {
                public function jsonSerialize(): string
                {
                    return 'Hello, world!';
                }

            },
        ];
    }
}
