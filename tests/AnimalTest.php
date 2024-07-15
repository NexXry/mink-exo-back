<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Animal;
use App\Entity\Race;
use App\Entity\Species;
use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnimalTest extends WebTestCase
{
    public function testAnimal(): void
    {
        $animal = new Animal();

        $species = new Species();
        $species->setName('lapin');

        $race = new Race();
        $race->setName('angora');

        $animal->setName('myrtile')
            ->setAge(1)
            ->setPriceHT(40)
            ->setPriceTTC(48)
            ->setDescription('Un petit lapin blanc')
            ->setSpecies($species)
            ->setRace($race)
            ->setStatus(Status::NOT_READY);

        $this->assertEquals($animal->getName(), 'myrtile');
        $this->assertEquals($animal->getAge(), 1);
        $this->assertEquals($animal->getPriceHT(), 40);
        $this->assertEquals($animal->getPriceTTC(), 48);
        $this->assertEquals($animal->getDescription(), 'Un petit lapin blanc');
        $this->assertEquals($animal->getSpecies(), $species);
        $this->assertEquals($animal->getRace(), $race);
        $this->assertEquals($animal->getStatus(), Status::NOT_READY);
    }
}
