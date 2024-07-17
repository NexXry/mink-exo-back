<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testImage(): void
    {
        $image = new Image();
        $image->setName('angora.png');

        $this->assertEquals($image->getName(), 'angora.png');
    }
}
