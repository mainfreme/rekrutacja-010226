<?php

declare(strict_types=1);

namespace App\Identity\Application\Command;

use App\Identity\Application\Exception\UserCannotUpdateException;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final class UpdateUserTokenHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    )
    {}

    /**
     * @param UpdateUserTokenCommand $command
     * @return void
     * @throws UserCannotUpdateException
     */
    public function __invoke(UpdateUserTokenCommand $command)
    {
        $userModel = $this->userRepository->updateToken($command->id, $command->token);
        if (NULL === $userModel) {
            throw new UserCannotUpdateException();
        }
    }
}
