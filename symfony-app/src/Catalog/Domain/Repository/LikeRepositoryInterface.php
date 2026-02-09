<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Repository;

use App\Catalog\Domain\Entity\Like;
use App\Catalog\Domain\Entity\Photo;
use App\Identity\Domain\Entity\User;

interface LikeRepositoryInterface
{
    public function setUser(?User $user): void;

    public function unlikePhoto(Photo $photo): void;

    public function hasUserLikedPhoto(Photo $photo): bool;

    public function createLike(Photo $photo): Like;

    public function updatePhotoCounter(Photo $photo, int $increment): void;
}
