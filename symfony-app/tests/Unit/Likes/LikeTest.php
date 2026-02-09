<?php

declare(strict_types=1);

namespace App\Tests\Unit\Likes;

use App\Catalog\Domain\Entity\Photo;
use App\Identity\Domain\Entity\User;
use App\Likes\Like;
use PHPUnit\Framework\TestCase;

class LikeTest extends TestCase
{
    public function testCreateLike(): void
    {
        $user = new User();
        $user->setUsername('liker');
        $user->setEmail('liker@example.com');

        $photo = new Photo();
        $photo->setImageUrl('https://example.com/photo.jpg');

        $like = new Like();
        $like->setUser($user);
        $like->setPhoto($photo);

        $this->assertSame($user, $like->getUser());
        $this->assertSame($photo, $like->getPhoto());
        $this->assertInstanceOf(\DateTimeInterface::class, $like->getCreatedAt());
    }
}
