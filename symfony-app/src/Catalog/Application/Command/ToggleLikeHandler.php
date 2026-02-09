<?php

declare(strict_types=1);

namespace App\Catalog\Application\Command;

use App\Catalog\Application\Exception\PhotoNotExistException;
use App\Catalog\Domain\Repository\LikeRepositoryInterface;
use App\Catalog\Domain\Repository\PhotoRepositoryInterface;
use App\Catalog\Domain\Service\Likes\LikeService;
use App\Identity\Application\Exception\UserNotFoundException;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final class ToggleLikeHandler
{
    public function __construct(
        private PhotoRepositoryInterface $photoRepository,
        private LikeRepositoryInterface $likeRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @throws PhotoNotExistException
     * @throws UserNotFoundException
     * @throws \App\Catalog\Application\Exception\LikeException
     */
    public function __invoke(ToggleLikeCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $photo = $this->photoRepository->findById($command->photoId);

        if ($photo === null) {
            throw new PhotoNotExistException();
        }

        $this->likeRepository->setUser($user);

        $likeService = new LikeService($this->likeRepository, $photo);
        $likeService->toggle();
    }
}
