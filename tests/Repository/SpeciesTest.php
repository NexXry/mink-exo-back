<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\AppFixtures;
use App\Repository\SpeciesRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SpeciesTest extends WebTestCase
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

        $speciesRepo = $container->get(SpeciesRepository::class);
        $countSpecies = $speciesRepo->count([]);

        $this->assertEquals(1, $countSpecies);
    }
}
