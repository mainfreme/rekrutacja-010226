<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Repository;

use App\Catalog\Domain\Entity\Photo;
use App\Identity\Domain\Entity\User;
use App\Shared\Application\Dto\PhotoFilterDto;

interface PhotoRepositoryInterface
{

    public function findById(int $id): ?Photo;

    /**
     * PhotoFilterDto $criteria
     * @return Photo[]
     */
    public function findAllWithUsers(PhotoFilterDto $criteria): array;

    /**
     * @param User $user
     * @param array<string|int> $photo
     * @return void
     */
    public function addPhoto(User $user, array $photo): void;

    public function photoExist(User $user, array $photo): bool;
}
