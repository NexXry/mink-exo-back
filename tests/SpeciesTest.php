<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Animal;
use App\Entity\Race;
use App\Entity\Species;
use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SpeciesTest extends WebTestCase
{
    public function testAnimal(): void
    {
        $species = new Species();
        $species->setName('lapin');

        $this->assertEquals($species->getName(), 'lapin');
    }
}
