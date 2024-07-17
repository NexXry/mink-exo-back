<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class SpeciesTest extends ApiTestCase
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

    public function testGetSpecies(): void
    {
        $response = static::createClient()->request('GET', '/api/species', ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(1, $response->toArray()["hydra:member"]);
        $this->assertTrue(array_key_exists('name', $response->toArray()["hydra:member"][0]));
    }

    public function testGetSpeciesById(): void
    {
        $response = static::createClient()->request('GET', '/api/species', ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $species = $response->toArray()["hydra:member"][0];
        $speciesId = $species['@id'];

        $response = static::createClient()->request('GET', $speciesId, ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['id' => $species['id']]);
    }

    public function testCreateSpecies(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('POST', '/api/species', [
            'headers' => ['Content-Type' => 'application/ld+json', 'Authorization' => 'Bearer ' . $this->jwtToken],
            'json' => [
                'name' => 'crocodile'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains(['name' => 'crocodile']);
    }

    public function testUpdateSpecies(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('GET', '/api/species', ['headers' => ['Accept' => 'application/ld+json'], 'auth_bearer' => $this->jwtToken]);
        $species = $response->toArray()["hydra:member"][0];
        $speciesId = $species['@id'];

        $response = static::createClient()->request('PATCH', $speciesId, [
            'headers' => ['Content-Type' => 'application/merge-patch+json', 'Authorization' => 'Bearer ' . $this->jwtToken],
            'json' => [
                'name' => 'crocodile du nil'
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['name' => 'crocodile du nil']);
    }

    public function testDeleteSpecies(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('GET', '/api/species', ['headers' => ['Accept' => 'application/ld+json'], 'auth_bearer' => $this->jwtToken]);
        $species = $response->toArray()["hydra:member"][0];
        $speciesId = $species['@id'];

        $response = static::createClient()->request('DELETE', $speciesId);
        $this->assertResponseStatusCodeSame(401);
        $response = static::createClient()->request('DELETE', $speciesId, ['headers' => ['Authorization' => 'Bearer ' . $this->jwtToken]]);
        $this->assertResponseStatusCodeSame(204);
    }
}
