<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SpeciesTest extends WebTestCase
{
    public function testSpecies(): void
    {
        $species = new Species();
        $species->setName('lapin');

        $this->assertEquals($species->getName(), 'lapin');
    }
}
