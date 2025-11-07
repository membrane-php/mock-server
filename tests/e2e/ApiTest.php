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
    #[TestDox('Calling undefined operations returns 522.')]
    public function youCannotCallUndefinedOperation(): void
    {
        $listPets = $this->callMocking('get', '/pets');
        self::assertSame(522, $listPets->getStatusCode());
    }

    #[Test]
    #[TestDox('Calling defined operations returns default.')]
    public function youCanCallDefinedOperation(): void
    {
        $expected = ['code' => 200, 'body' => '{"id":5,"name":"Blink"}'];

        $addListPets = $this->callApi(
            'post',
            '/operation/listPets',
            ['default' => ['response' => $expected]],
        );
        self::assertSame(201, $addListPets->getStatusCode());

        $listPets = $this->callMocking('get', '/pets');

        self::assertSame($expected['code'], $listPets->getStatusCode());
        self::assertSame($expected['body'], (string)$listPets->getBody());
    }

    #[Test]
    #[TestDox('Calling undefined operations wont return defined operations.')]
    public function youCannotGetMismatchedOperation(): void
    {
        $addListPets = $this->callApi(
            'post',
            '/operation/listPets',
            ['default' => ['response' => ['code' => 200]]],
        );

        $showPetById = $this->callMocking('get', '/pets/5');

        self::assertSame(522, $showPetById->getStatusCode());
    }

    #[Test]
    #[TestDox('You can delete an operation, so that it cannot be called.')]
    public function youCanDeleteOperation(): void
    {
        $addListPets = $this->callApi(
            'post',
            '/operation/listPets',
            ['default' => ['response' => ['code' => 200]]]
        );

        $deleteListPets = $this->callApi('delete', '/operation/listPets');
        self::assertSame(204, $deleteListPets->getStatusCode());

        $listPets = $this->callMocking('get', '/pets');
        self::assertSame(522, $listPets->getStatusCode());
    }

    #[Test]
    #[TestDox('You can match a defined matcher.')]
    public function youCanMatchDefinedMatcher(): void
    {
        $expected = [
            'code' => 200,
            'headers' => ['Content-type' => 'application/json'],
            'body' => '{"id":6,"name":"Harley"}',
        ];

        $addShowPetById = $this->callApi(
            'post',
            '/operation/showPetById',
            ['default' => ['response' => ['code' => 404]]],
        );

        $addMatcherForId6 = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            [
                'matcher' => [
                    'type' => 'equals',
                    'args' => ['field' => ['path', 'petId'], 'value' => 6],
                ],
                'response' => $expected,
            ]
        );
        self::assertSame(201, $addMatcherForId6->getStatusCode());

        $showPetById6 = $this->callMocking('get', '/pets/6');
        self::assertSame($expected['code'], $showPetById6->getStatusCode());
        self::assertSame($expected['body'], (string)$showPetById6->getBody());
    }

    #[Test]
    #[TestDox('You can match any of several defined matchers.')]
    public function youCanMatchMultipleDefinedMatchers(): void
    {
        $addShowPetById = $this->callApi(
            'post',
            '/operation/showPetById',
            ['default' => ['response' => ['code' => 404]]],
        );

        $matchId6 = [
            'matcher' => [
                'type' => 'equals',
                'args' => ['field' => ['path', 'petId'], 'value' => 6],
            ],
            'response' => [
                'code' => 200,
                'headers' => ['Content-type' => 'application/json'],
                'body' => '{"id":6,"name":"Harley"}',
            ],
        ];
        $matchNegativeIds = [
            'matcher' => [
                'type' => 'less-than',
                'args' => ['field' => ['path', 'petId'], 'limit' => 0],
            ],
            'response' => ['code' => 401],
        ];
        $addMatcherForId6 = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            $matchId6,
        );
        $addMatcherForNegativeIds = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            $matchNegativeIds,
        );

        $showPetById6 = $this->callMocking('get', '/pets/6');
        self::assertSame($matchId6['response']['code'], $showPetById6->getStatusCode());
        self::assertSame($matchId6['response']['body'], (string)$showPetById6->getBody());

        $showPetByIdMinus1 = $this->callMocking('get', '/pets/-1');
        self::assertSame($matchNegativeIds['response']['code'], $showPetByIdMinus1->getStatusCode());

    }

    #[Test]
    #[TestDox('You can fail to match defined matchers, and fall back to default.')]
    public function youCanFallbackToDefault(): void
    {
        $this->callApi(
            'post',
            '/operation/showPetById',
            ['default' => ['response' => ['code' => 404]]],
        );

        $body = '{"id":6,"name":"Harley"}';

        $response = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            [
                'matcher' => [
                    'type' => 'equals',
                    'args' => ['field' => ['path', 'petId'], 'value' => 6],
                ],
                'response' => [
                    'code' => 200,
                    'headers' => ['Content-type' => 'application/json'],
                    'body' => $body,
                ],
            ]
        );

        self::assertSame(201, $response->getStatusCode());

        $response = $this->callMocking('get', '/pets/33');

        self::assertSame(404, $response->getStatusCode());
    }

    //TODO if you delete a matcher, you should fallback to default
    //TODO if you delete the operation, without deleting the matcher explicitly, it should still be deleted
    #[Test]
    public function youCannotMatchDeletedMatcher(): void
    {
        $this->callApi(
            'post',
            '/operation/showPetById',
            ['default' => ['response' => ['code' => 404]]],
        );

        $body = '{"id":6,"name":"Harley"}';

        $response = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            [
                'matcher' => [
                    'type' => 'equals',
                    'args' => ['field' => ['path', 'petId'], 'value' => 6],
                ],
                'response' => [
                    'code' => 200,
                    'headers' => ['Content-type' => 'application/json'],
                    'body' => $body,
                ],
            ]
        );

        self::assertSame(201, $response->getStatusCode());

        $response = $this->callMocking('get', '/pets/6');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame($body, (string)$response->getBody());
    }

    //TODO if you define a matcher on an operation, delete and recreate the operation, the matcher is gone

    /** @param array<mixed> $body */
    private function callApi(
        string $method,
        string $relativeUri,
        array $body = [],
    ): ResponseInterface {
        $client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8080',
            GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
        ]);

        return $client->request(
            $method,
            $relativeUri,
            [GuzzleHttp\RequestOptions::JSON => $body],
        );
    }

    /** @param array<mixed> $body */
    private function callMocking(
        string $method,
        string $relativeUri,
        array $body = [],
    ): ResponseInterface {
        $client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8081',
            GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
        ]);

        return $client->request(
            $method,
            $relativeUri,
            [GuzzleHttp\RequestOptions::JSON => $body],
        );
    }
}
