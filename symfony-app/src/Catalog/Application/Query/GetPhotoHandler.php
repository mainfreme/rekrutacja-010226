<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query;

use App\Catalog\Application\Exception\PhotoNotExistException;
use App\Catalog\Domain\Entity\Photo;
use App\Catalog\Domain\Repository\PhotoRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class GetPhotoHandler
{
    public function __construct(
        private readonly PhotoRepositoryInterface $photoRepository
    ){}

    /**
     * @param GetPhotoQuery $query
     * @return Photo
     * @throws PhotoNotExistException
     */
    public function __invoke(GetPhotoQuery $query): Photo
    {
        $photo = $this->photoRepository->findById($query->id);

        if (!$photo) {
            throw new PhotoNotExistException();
        }

        return $photo;
    }
}
