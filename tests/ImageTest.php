<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageTest extends WebTestCase
{
    public function testAnimal(): void
    {
        $image = new Image();
        $image->setName('angora.png');

        $this->assertEquals($image->getName(), 'angora.png');
    }
}
