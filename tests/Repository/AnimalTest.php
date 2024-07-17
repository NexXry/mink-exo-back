<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\AppFixtures;
use App\Repository\AnimalRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnimalTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    public function testSomething(): void
    {
        self::bootKernel();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AppFixtures::class
        ]);
        $container = static::getContainer();

        $animalRepo = $container->get(AnimalRepository::class);
        $countAnimal = $animalRepo->count([]);
   
        $this->assertEquals(1, $countAnimal);
    }
}
