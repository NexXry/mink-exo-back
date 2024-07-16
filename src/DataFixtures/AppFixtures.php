<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\Image;
use App\Entity\Race;
use App\Entity\Species;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $angora = new Race();
        $angora->setName('angora');

        $species = new Species();
        $species->setName('lapin');

        $image = new Image();
        $image->setName('angora.png');

        $animal = new Animal();
        $animal->setName('myrtille')
            ->setAge(1)
            ->setPriceHT(40)
            ->setPriceTTC(48)
            ->setDescription('Un petit lapin blanc')
            ->setSpecies($species)
            ->setRace($angora)
            ->setStatus(Status::NOT_READY)
            ->addImage($image);

        $manager->persist($angora);
        $manager->persist($species);
        $manager->persist($image);
        $manager->persist($animal);

        $manager->flush();
    }
}
