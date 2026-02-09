<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Service\Likes;

use App\Catalog\Application\Exception\LikeException;
use App\Catalog\Domain\Entity\Photo;
use App\Catalog\Domain\Repository\LikeRepositoryInterface;
use App\Catalog\Domain\Service\Likes\Strategy\AddLikeStrategy;
use App\Catalog\Domain\Service\Likes\Strategy\LikeStrategyInterface;
use App\Catalog\Domain\Service\Likes\Strategy\RemoveLikeStrategy;

class LikeService
{
    private LikeStrategyInterface $strategy;

    public function __construct(
        private LikeRepositoryInterface $likeRepository,
        private readonly Photo $photo,
    ) {
        $this->resolveStrategy();
    }

    /**
     * Automatycznie wybiera strategię na podstawie aktualnego stanu
     */
    private function resolveStrategy(): void
    {
        $hasUserLike = $this->likeRepository->hasUserLikedPhoto($this->photo);

        $this->strategy = $hasUserLike
            ? new RemoveLikeStrategy($this->likeRepository)
            : new AddLikeStrategy($this->likeRepository);
    }

    /**
     * Wykonuje akcję - klasa sama decyduje czy dodać czy usunąć like
     *
     * @throws LikeException
     */
    public function toggle(): self
    {
        $this->strategy->execute($this->photo);

        return $this;
    }

    /**
     * Zwraca informację o wykonanej akcji (przydatne dla kontrolera)
     */
    public function wasLiked(): bool
    {
        return $this->strategy instanceof AddLikeStrategy;
    }
}
