<?php

declare(strict_types=1);

namespace App\Identity\Application\Query;

use App\Identity\Domain\Entity\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Repository\AuthRepositoryInterface;
use App\Identity\Application\Exception\InvalidTokenException;
use App\Identity\Application\Exception\UserNotFoundException;

#[AsMessageHandler(bus: 'query.bus')]
final class LoginHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthRepositoryInterface $tokenRepository,
    ) {}

    /**
     * @throws UserNotFoundException
     * @throws InvalidTokenException
     */
    public function __invoke(LoginQuery $query): User
    {
        $token = $this->tokenRepository->findByToken($query->token);

        if (!$token) {
            throw new InvalidTokenException();
        }

        $user = $this->userRepository->findByUsername($query->username);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
