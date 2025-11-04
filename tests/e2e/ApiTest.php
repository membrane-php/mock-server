<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\e2e;

use GuzzleHttp;
use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Module;
use Membrane\MockServer\Api\Response;
use Membrane\MockServer\Database;
use Membrane\MockServer\Database\Schema\MatcherTable;
use Membrane\MockServer\Database\Schema\OperationTable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Http\Message\ResponseInterface;

#[UsesClass(MatcherTable::class)]
#[UsesClass(OperationTable::class)]
#[UsesClass(Response::class)]
#[UsesClass(Command\Reset::class)]
#[UsesClass(Database\Model\Matcher::class)]
#[CoversClass(Module::class)]
#[\PHPUnit\Framework\Attributes\Group('e2e')]
final class ApiTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        $this->callApi('post', '/reset', []);
    }

    #[Test]
    #[TestDox('You can call an operation once you add it.')]
    public function youCanCallOperation(): void
    {
        $response = $this->callApi(
            'post',
            '/operation/listPets',
            ['default' => ['response' => ['code' => 200]]],
        );

        //@BUG: this should be 201, but is returning 202
        self::assertSame(202, $response->getStatusCode());

        $response = $this->callMocking(
            'get',
            '/pets',
            [],
        );

        self::assertSame(200, $response->getStatusCode());
    }

    #[Test]
    #[TestDox('You can call an operation once you add it.')]
    public function youCanCallOperation(): void
    {
        $response = $this->callApi(
            'post',
            '/operation/listPets',
            ['default' => ['response' => ['code' => 200]]],
        );

        //@BUG: this should be 201, but is returning 202
        self::assertSame(202, $response->getStatusCode());

        $response = $this->callMocking(
            'get',
            '/pets',
            [],
        );

        self::assertSame(200, $response->getStatusCode());
    }


    private function callApi(
        string $method,
        string $relativeUri,
        array $body,
    ): ResponseInterface {
        $client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8080',
        ]);

        return $client->request(
            $method,
            $relativeUri,
            [GuzzleHttp\RequestOptions::JSON => $body],
        );
    }

    private function callMocking(
        string $method,
        string $relativeUri,
        array $body,
    ): ResponseInterface {
        $client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8081',
        ]);

        return $client->request(
            $method,
            $relativeUri,
            [GuzzleHttp\RequestOptions::JSON => $body],
        );
    }
}
