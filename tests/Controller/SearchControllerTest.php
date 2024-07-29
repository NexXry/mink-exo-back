<?php

namespace App\Tests\Controller;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SearchControllerTest extends WebTestCase
{
    public function testIndexNoQuery()
    {
        $client = static::createClient();
        $client->request('GET', '/api/search');

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('No query found', $client->getResponse()->getContent());
    }

    public function testIndexWithQuery()
    {
        $client = static::createClient();

        $animalRepository = $this->createMock(AnimalRepository::class);
        $animalRepository->expects($this->once())
            ->method('findSearchAnimal')
            ->with($this->equalTo('test'))
            ->willReturn(['animal1', 'animal2']);

        self::getContainer()->set(AnimalRepository::class, $animalRepository);

        $client->request('GET', '/api/search', ['q' => 'test']);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('animal1', $client->getResponse()->getContent());
        $this->assertStringContainsString('animal2', $client->getResponse()->getContent());
    }
}
