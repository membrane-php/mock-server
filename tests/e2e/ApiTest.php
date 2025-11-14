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
    public function youWontMatchUndefinedOperation(): void
    {
        $listPets = $this->callMocking('get', '/pets');
        self::assertSame(522, $listPets->getStatusCode());
    }

    #[Test]
    #[TestDox('Calling defined operations returns default.')]
    public function youMayMatchDefinedOperation(): void
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
        self::assertSame($expected['body'], (string) $listPets->getBody());
    }

    #[Test]
    #[TestDox('Calling undefined operations wont return defined operations.')]
    public function youMayNotMatchDefinedOperation(): void
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
    public function youCanDeleteDefinedOperation(): void
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
    public function youMayMatchDefinedMatcher(): void
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
    #[TestDox('Avoiding matching defined matchers, will fall back to default.')]
    public function youMayNotMatchDefinedMatcher(): void
    {
        $default = ['response' => ['code' => 404]];
        $addShowPetById = $this->callApi(
            'post',
            '/operation/showPetById',
            ['default' => $default],
        );

        $addMatcherForId6 = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            [
                'matcher' => [
                    'type' => 'equals',
                    'args' => ['field' => ['path', 'petId'], 'value' => 6],
                ],
                'response' => ['code' => 200],
            ],
        );
        $addMatcherForNegativeIds = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            [
                'matcher' => [
                    'type' => 'less-than',
                    'args' => ['field' => ['path', 'petId'], 'limit' => 0],
                ],
                'response' => ['code' => 401],
            ],
        );

        self::assertSame(
            $default['response']['code'],
            $this->callMocking('get', '/pets/33')->getStatusCode(),
        );
    }

    #[Test]
    #[TestDox('You can match any of several defined matchers.')]
    public function youMayMatchMultipleDefinedMatchers(): void
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

    //#[Test]
    //#[TestDox('If you match multiple matchers, the one first defined wins.')]
    //public function youWillMatchFirstDefinedMatchingMatcher(): void
    //{
    //    $addShowPetById = $this->callApi(
    //        'post',
    //        '/operation/showPetById',
    //        ['default' => ['response' => ['code' => 404]]],
    //    );
    //
    //    $matchIdGreaterThan1 = [
    //        'matcher' => [
    //            'type' => 'greater-than',
    //            'args' => ['field' => ['path', 'petId'], 'value' => 1],
    //        ],
    //        'response' => [
    //            'code' => 200,
    //            'headers' => ['Content-type' => 'application/json'],
    //            'body' => '{"id":6,"name":"Harley"}',
    //        ],
    //    ];
    //    $matchIdLessThan3 = [
    //        'matcher' => [
    //            'type' => 'less-than',
    //            'args' => ['field' => ['path', 'petId'], 'limit' => 3],
    //        ],
    //        'response' => ['code' => 401],
    //    ];
    //    $addMatcherForId6 = $this->callApi(
    //        'post',
    //        '/operation/showPetById/matcher',
    //        $matchIdGreaterThan1,
    //    );
    //    $addMatcherForNegativeIds = $this->callApi(
    //        'post',
    //        '/operation/showPetById/matcher',
    //        $matchIdLessThan3,
    //    );
    //
    //    $showPetById6 = $this->callMocking('get', '/pets/6');
    //    self::assertSame($matchIdGreaterThan1['response']['code'], $showPetById6->getStatusCode());
    //    self::assertSame($matchIdGreaterThan1['response']['body'], (string)$showPetById6->getBody());
    //
    //    $showPetByIdMinus1 = $this->callMocking('get', '/pets/-1');
    //    self::assertSame($matchIdLessThan3['response']['code'], $showPetByIdMinus1->getStatusCode());
    //}

    #[Test]
    public function youWontDeleteOperationWhenDeletingMatcher(): void
    {
        $default = ['response' => ['code' => 404]];
        $addShowPetById = $this->callApi(
            'post',
            '/operation/showPetById',
            ['default' => $default],
        );

        $addMatcherForId6 = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            [
                'matcher' => [
                    'type' => 'equals',
                    'args' => ['field' => ['path', 'petId'], 'value' => 6],
                ],
                'response' => ['code' => 200],
            ],
        );

        $matcherForId6 = json_decode((string)$addMatcherForId6->getBody());


        $deleteMatcherForId6 = $this->callApi(
            'delete',
            "/operation/showPetById/matcher/$matcherForId6->id"
        );

        self::assertSame(204, $deleteMatcherForId6->getStatusCode());

        $showPetById6 = $this->callMocking('get', '/pets/6');
        self::assertSame($default['response']['code'], $showPetById6->getStatusCode());
    }

    #[Test]
    public function youWillDeleteMatchersWhenDeletingOperation(): void
    {
        $default = ['response' => ['code' => 404]];
        $addShowPetById = fn () => $this->callApi(
            'post',
            '/operation/showPetById',
            ['default' => $default],
        );

        $addShowPetById();

        $addMatcherForId6 = $this->callApi(
            'post',
            '/operation/showPetById/matcher',
            [
                'matcher' => [
                    'type' => 'equals',
                    'args' => ['field' => ['path', 'petId'], 'value' => 6],
                ],
                'response' => ['code' => 200],
            ],
        );

        $deleteShowPetById = $this->callApi('delete', '/operation/showPetById');

        $addShowPetById();

        $showPetById6 = $this->callMocking('get', '/pets/6');
        self::assertSame($default['response']['code'], $showPetById6->getStatusCode());
    }

    /** @param array<mixed> $body */
    private function callApi(
        string $method,
        string $relativeUri,
        array $body = [],
    ): ResponseInterface {
        $client = new GuzzleHttp\Client([
            'base_uri' => 'http://mockserver:8080',
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
            'base_uri' => 'http://mockserver:8081',
            GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
        ]);

        return $client->request(
            $method,
            $relativeUri,
            [GuzzleHttp\RequestOptions::JSON => $body],
        );
    }
}
