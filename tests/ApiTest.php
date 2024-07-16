<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Kernel;

class ApiTest extends ApiTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    public function testAnimal(): void
    {
        $response = static::createClient()->request('GET', '/animals');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        dd($response);
    }
}
