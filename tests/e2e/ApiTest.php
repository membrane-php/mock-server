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
        self::assertSame(522, $this->callMocking('get', '/pets')->getStatusCode());
    }

    #[Test]
    #[TestDox('Calling defined operations returns default.')]
    public function youCanCallDefinedOperation(): void
    {
        $body = '{"id":5,"name":"Blink"}';

        $response = $this->callApi(
            'post',
            '/operation/listPets',
            ['default' => ['response' => ['code' => 200, 'body' => $body]]],
        );

       self::assertSame(201, $response->getStatusCode());

       $response = $this->callMocking('get', '/pets', []);

       self::assertSame(200, $response->getStatusCode());
       self::assertSame($body, (string) $response->getBody());
    }

    #[Test]
    #[TestDox('You can call an operation once you add it.')]
    public function youCanDeleteOperation(): void
    {
        $this->callApi(
            'post',
            '/operation/listPets',
            ['default' => ['response' => ['code' => 200]]],
        );

        $this->callApi('DELETE', '/operation/listPets');

        self::assertSame(522, $this->callMocking('get', '/pets')->getStatusCode());
    }

    //TODO if you define several matchers, the appropriate match is made

    //TODO if you define a matcher, but don't match it, you fall back to default
    #[Test]
     public function youCanMatchDefinedMatcher(): void
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

    #[Test]
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
    public function youCannotMatchUndefinedMatcher(): void
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
