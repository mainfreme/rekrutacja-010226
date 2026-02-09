<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Service\Likes\Strategy;

use App\Catalog\Application\Exception\LikeException;
use App\Catalog\Domain\Entity\Photo;
use App\Catalog\Domain\Repository\LikeRepositoryInterface;

final class AddLikeStrategy implements LikeStrategyInterface
{
    public function __construct(
        private readonly LikeRepositoryInterface $likeRepository,
    ) {}

    /**
     * @throws LikeException
     */
    public function execute(Photo $photo): void
    {
        try {
            $this->likeRepository->createLike($photo);
            $this->likeRepository->updatePhotoCounter($photo, 1);
        } catch (\Throwable $e) {
            throw new LikeException('Coś poszło nie tak z polubieniem zdjęcia');
        }
    }
}
