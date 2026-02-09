<?php

declare(strict_types=1);

namespace App\Identity\Application\Query;

use App\Identity\Application\Exception\UserNotFoundException;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Entity\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class GetUserByIdHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(GetUserByIdQuery $query): User
    {
        $user = $this->userRepository->findById($query->id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
