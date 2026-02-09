<?php

declare(strict_types=1);

namespace App\Identity\Application\Command;

use App\Identity\Domain\Repository\AuthRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final class CheckAuthTokenHandler
{
    public function __construct(
        private readonly AuthRepositoryInterface $authTokenRepository
    ) {}

    public function __invoke(CheckAuthTokenCommand $command): bool
    {
        $authToken = $this->authTokenRepository->findByToken($command->token);

        if (!$authToken) {
            return false;
        }

        return true;
    }
}
