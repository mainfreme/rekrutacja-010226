<?php

declare(strict_types=1);

namespace App\Catalog\Application\Command;

use App\Identity\Domain\Entity\User;
use App\Catalog\Application\Service\DataImporter;
use App\Catalog\Domain\Exception\AccessDeniedException;
use App\Catalog\Domain\Repository\PhotoRepositoryInterface;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Application\Exception\UserNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final class ImportDataHandler
{
    public function __construct(
        private readonly DataImporter             $importer,
        private readonly UserRepositoryInterface  $userRepository,
        private readonly PhotoRepositoryInterface $photoRepository
    ){}

    /**
     * @param ImportDataCommand $command
     * @return void
     * @throws AccessDeniedException
     * @throws UserNotFoundException
     */
    public function __invoke(ImportDataCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);
        if (NULL === $user) {
            throw new UserNotFoundException();
        }

        $photos = $this->importer->execute($user, $command->provider);

        if (isset($photos['errors']['detail']) && !empty($photos['errors']['detail'])) {
            throw new AccessDeniedException($photos['errors']['detail']);
        }

        /**
         * array<int|string> $photo
         */
        foreach ($photos as $photo) {
            $this->addPhoto($user, $photo);
        }
    }

    private function addPhoto(User $user, array $photo): void
    {
        $photoExist = $this->photoRepository->photoExist($user, $photo);
        if (!$photoExist) {
            $this->photoRepository->addPhoto($user, $photo);
        }
    }
}
