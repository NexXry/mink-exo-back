<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class RaceTest extends ApiTestCase
{
    private string $jwtToken;
    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AppFixtures::class
        ]);
    }

    public static function userLoggedIn(): string
    {
        $response = static::createClient()->request('POST', '/auth', ['json' => [
            'email' => 'mink@mink.com',
            'password' => 'mink',
        ]]);
        return $response->toArray()['token'];
    }

    public function testGetRace(): void
    {
        $response = static::createClient()->request('GET', '/api/races', ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(1, $response->toArray()["hydra:member"]);
        $this->assertTrue(array_key_exists('name', $response->toArray()["hydra:member"][0]));
    }

    public function testGetRaceById(): void
    {
        $response = static::createClient()->request('GET', '/api/races', ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $race = $response->toArray()["hydra:member"][0];
        $raceId = $race['@id'];

        $response = static::createClient()->request('GET', $raceId, ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['id' => $race['id']]);
    }

    public function testCreateRace(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('POST', '/api/races', [
            'headers' => ['Content-Type' => 'application/ld+json', 'Authorization' => 'Bearer ' . $this->jwtToken],
            'json' => [
                'name' => 'martre'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains(['name' => 'martre']);
    }

    public function testUpdateRace(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('GET', '/api/races', ['headers' => ['Accept' => 'application/ld+json'], 'auth_bearer' => $this->jwtToken]);
        $race = $response->toArray()["hydra:member"][0];
        $raceId = $race['@id'];

        $response = static::createClient()->request('PATCH', $raceId, [
            'headers' => ['Content-Type' => 'application/merge-patch+json', 'Authorization' => 'Bearer ' . $this->jwtToken],
            'json' => [
                'name' => 'martre foina'
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['name' => 'martre foina']);
    }

    public function testDeleteRace(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('GET', '/api/races', ['headers' => ['Accept' => 'application/ld+json'], 'auth_bearer' => $this->jwtToken]);
        $race = $response->toArray()["hydra:member"][0];
        $raceId = $race['@id'];

        $response = static::createClient()->request('DELETE', $raceId);
        $this->assertResponseStatusCodeSame(401);
        $response = static::createClient()->request('DELETE', $raceId, ['headers' => ['Authorization' => 'Bearer ' . $this->jwtToken]]);

        $this->assertResponseStatusCodeSame(204);
    }
}
