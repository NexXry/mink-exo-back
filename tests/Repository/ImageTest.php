<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\AppFixtures;
use App\Repository\AnimalRepository;
use App\Repository\ImageRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    public function testCount(): void
    {
        self::bootKernel();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AppFixtures::class
        ]);
        $container = static::getContainer();

        $imageRepo = $container->get(ImageRepository::class);
        $countImage = $imageRepo->count([]);

        $this->assertEquals(1, $countImage);
    }

    public function testFindByName(): void
    {
        self::bootKernel();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AppFixtures::class
        ]);
        $container = static::getContainer();

        $imageRepo = $container->get(ImageRepository::class);
        $images = $imageRepo->findBy(['name' => 'angora.png']);

        $this->assertNotEmpty($images);
        $this->assertEquals('angora.png', $images[0]->getName());
    }

    public function testFindByAnimal(): void
    {
        self::bootKernel();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AppFixtures::class
        ]);
        $container = static::getContainer();

        $imageRepo = $container->get(ImageRepository::class);
        $animalRepo = $container->get(AnimalRepository::class);
        $animal = $animalRepo->findOneBy(['name' => 'myrtille']);
        $images = $imageRepo->findBy(['animal' => $animal]);

        $this->assertNotEmpty($images);
        $this->assertEquals($animal->getId(), $images[0]->getAnimal()->getId());
    }
}
