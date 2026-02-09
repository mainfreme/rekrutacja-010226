<?php

declare(strict_types=1);

namespace App\Tests\Unit\Likes;

use App\Entity\Photo;
use App\Entity\User;
use App\Likes\Like;
use App\Likes\LikeRepositoryInterface;
use App\Likes\LikeService;
use PHPUnit\Framework\TestCase;

class LikeServiceTest extends TestCase
{
    public function testExecuteCreatesLikeAndUpdatesCounter(): void
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('test@example.com');

        $photo = new Photo();
        $photo->setImageUrl('https://example.com/photo.jpg');
        $photo->setUser($user);

        $likeRepository = $this->createMock(LikeRepositoryInterface::class);

        $likeRepository
            ->expects($this->once())
            ->method('createLike')
            ->with($photo)
            ->willReturn(new Like());

        $likeRepository
            ->expects($this->once())
            ->method('updatePhotoCounter')
            ->with($photo, 1);

        $likeService = new LikeService($likeRepository);
        $likeService->execute($photo);
    }

    public function testExecuteThrowsExceptionOnError(): void
    {
        $photo = new Photo();
        $photo->setImageUrl('https://example.com/photo.jpg');

        $likeRepository = $this->createMock(LikeRepositoryInterface::class);
        $likeRepository
            ->method('createLike')
            ->willThrowException(new \RuntimeException('DB error'));

        $likeService = new LikeService($likeRepository);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Something went wrong while liking the photo');

        $likeService->execute($photo);
    }
}
