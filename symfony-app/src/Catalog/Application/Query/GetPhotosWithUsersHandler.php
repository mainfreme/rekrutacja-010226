<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query;

use App\Catalog\Domain\Repository\PhotoRepositoryInterface;
use App\Catalog\Application\Exception\PhotoNotFindException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Catalog\Domain\Entity\Photo;

#[AsMessageHandler(bus: 'query.bus')]
final class GetPhotosWithUsersHandler
{
    public function __construct(
        private readonly PhotoRepositoryInterface $photoRepository
    ){}

    /**
     * @return Photo[]
     * @throws PhotoNotFindException
     */
    public function __invoke(GetPhotosWithUsersQuery $query): array
    {
        $photos = $this->photoRepository->findAllWithUsers($query->criteria);
        if (empty($photos)) {
            throw new PhotoNotFindException();
        }

        return $photos;
    }
}
