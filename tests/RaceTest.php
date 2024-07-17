<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Race;
use PHPUnit\Framework\TestCase;

class RaceTest extends TestCase
{
    public function testRace(): void
    {
        $race = new Race();
        $race->setName('angora');

        $this->assertEquals($race->getName(), 'angora');
    }
}
