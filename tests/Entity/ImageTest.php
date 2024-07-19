<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Image;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageTest extends TestCase
{

    public function testImage(): void
    {
        $image = new Image();
        $image->setName('angora.png');
        $image->setImageFile(new UploadedFile('../Fixture/ch.jpg','ch.jpg'));

        $this->assertEquals($image->getName(), 'angora.png');
        $this->assertEquals($image->getImageFile()->getFilename(), 'ch.jpg');
    }
}
