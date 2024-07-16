<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Race;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RaceTest extends WebTestCase
{
    public function testRace(): void
    {
        $race = new Race();
        $race->setName('angora');

        $this->assertEquals($race->getName(), 'angora');
    }
}
