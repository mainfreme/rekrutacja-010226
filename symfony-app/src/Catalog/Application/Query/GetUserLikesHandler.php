<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query;

use App\Catalog\Domain\Repository\LikeRepositoryInterface;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler(bus: 'query.bus')]
final class GetUserLikesHandler
{
    public function __construct(
        private LikeRepositoryInterface $likeRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @return array<int, bool> Map of photoId => hasLiked
     */
    public function __invoke(GetUserLikesQuery $query): array
    {
        $user = $this->userRepository->findById($query->userId);

        if ($user === null) {
            return [];
        }

        $this->likeRepository->setUser($user);

        $userLikes = [];
        foreach ($query->photos as $photo) {
            $userLikes[$photo->getId()] = $this->likeRepository->hasUserLikedPhoto($photo);
        }

        return $userLikes;
    }
}
