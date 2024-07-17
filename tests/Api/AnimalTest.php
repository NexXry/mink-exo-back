<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\AppFixtures;
use App\Entity\Status;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class AnimalTest extends ApiTestCase
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

    public function testGetAnimal(): void
    {
        $response = static::createClient()->request('GET', '/api/animals', ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(1, $response->toArray()["hydra:member"]);
        $this->assertFalse(array_key_exists('priceHT', $response->toArray()["hydra:member"][0]));
        $this->assertTrue(array_key_exists('priceTTC', $response->toArray()["hydra:member"][0]));
    }

    public function testGetAnimalHasAdmin(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('GET', '/api/animals', ['headers' => ['Accept' => 'application/ld+json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(1, $response->toArray()["hydra:member"]);
        $this->assertTrue(array_key_exists('priceHT', $response->toArray()["hydra:member"][0]));
        $this->assertTrue(array_key_exists('priceTTC', $response->toArray()["hydra:member"][0]));
    }

    public function testGetAnimalById(): void
    {
        $response = static::createClient()->request('GET', '/api/animals', ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $animal = $response->toArray()["hydra:member"][0];
        $animalId = $animal['@id'];

        $response = static::createClient()->request('GET', $animalId, ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['id' => $animal['id']]);
    }

    public function testCreateAnimal(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('POST', '/api/animals', [
            'headers' => ['Content-Type' => 'application/ld+json', 'Authorization' => 'Bearer ' . $this->jwtToken],
            'json' => [
                'name' => 'Lion',
                'age' => 5,
                'description' => 'A big cat',
                'priceHT' => 1000.0,
                'priceTTC' => 1200.0,
                'species' => '/api/species/1',
                'race' => '/api/races/1',
                'status' => Status::NOT_READY
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains(['name' => 'Lion']);
    }

    public function testUpdateAnimal(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('GET', '/api/animals', ['headers' => ['Accept' => 'application/ld+json'], 'auth_bearer' => $this->jwtToken]);
        $animal = $response->toArray()["hydra:member"][0];
        $animalId = $animal['@id'];

        $response = static::createClient()->request('PATCH', $animalId, [
            'headers' => ['Content-Type' => 'application/merge-patch+json', 'Authorization' => 'Bearer ' . $this->jwtToken],
            'json' => [
                'name' => 'Updated Lion'
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['name' => 'Updated Lion']);
    }

    public function testDeleteAnimal(): void
    {
        $this->jwtToken = $this->userLoggedIn();
        $response = static::createClient()->request('GET', '/api/animals', ['headers' => ['Accept' => 'application/ld+json'], 'auth_bearer' => $this->jwtToken]);
        $animal = $response->toArray()["hydra:member"][0];
        $animalId = $animal['@id'];

        $response = static::createClient()->request('DELETE', $animalId);
        $this->assertResponseStatusCodeSame(401);
        $response = static::createClient()->request('DELETE', $animalId, ['headers' => ['Authorization' => 'Bearer ' . $this->jwtToken]]);
        $this->assertResponseStatusCodeSame(204);
    }
}
