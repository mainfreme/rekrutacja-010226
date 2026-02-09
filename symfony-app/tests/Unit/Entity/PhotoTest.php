<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Catalog\Domain\Entity\Photo;
use App\Identity\Domain\Entity\User;
use PHPUnit\Framework\TestCase;

class PhotoTest extends TestCase
{
    public function testCreatePhoto(): void
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('test@example.com');

        $photo = new Photo();
        $photo->setImageUrl('https://example.com/photo.jpg');
        $photo->setUser($user);
        $photo->setDescription('Wakacje 2024');
        $photo->setLocation('Kraków');

        $this->assertEquals('https://example.com/photo.jpg', $photo->getImageUrl());
        $this->assertSame($user, $photo->getUser());
        $this->assertEquals('Wakacje 2024', $photo->getDescription());
        $this->assertEquals('Kraków', $photo->getLocation());
    }

    public function testLikeCounter(): void
    {
        $photo = new Photo();
        $photo->setImageUrl('https://example.com/photo.jpg');

        $this->assertEquals(0, $photo->getLikeCounter());

        $photo->setLikeCounter(5);
        $this->assertEquals(5, $photo->getLikeCounter());

        $photo->setLikeCounter($photo->getLikeCounter() + 1);
        $this->assertEquals(6, $photo->getLikeCounter());
    }
}
