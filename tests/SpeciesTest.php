<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Species;
use PHPUnit\Framework\TestCase;

class SpeciesTest extends TestCase
{
    public function testSpecies(): void
    {
        $species = new Species();
        $species->setName('lapin');

        $this->assertEquals($species->getName(), 'lapin');
    }
}
