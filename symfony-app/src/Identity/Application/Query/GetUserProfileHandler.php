<?php

declare(strict_types=1);

namespace App\Identity\Application\Query;

use App\Identity\Application\Exception\UserNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Entity\User;

#[AsMessageHandler(bus: 'query.bus')]
final class GetUserProfileHandler
{

    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(GetUserProfileQuery $query): User
    {
        $user = $this->userRepository->findById($query->id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
