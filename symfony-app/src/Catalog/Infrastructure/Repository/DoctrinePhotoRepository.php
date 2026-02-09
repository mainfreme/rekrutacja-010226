<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Entity\Photo;
use App\Catalog\Domain\Repository\PhotoRepositoryInterface;
use App\Shared\Application\Dto\PhotoFilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePhotoRepository extends ServiceEntityRepository implements PhotoRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    /**
     * @param int $id
     * @return Photo|null
     */
    public function findById(int $id): ?Photo
    {
        return $this->createQueryBuilder('p')
            ->join('p.user', 'u')
            ->addSelect('u')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * PhotoFilterDto $photoFilterDto
     * @return Photo[]
     */
    public function findAllWithUsers(PhotoFilterDto $photoFilterDto): array
    {
        $query = $this->createQueryBuilder('p')
            ->join('p.user', 'u')
            ->addSelect('u');

        if ($photoFilterDto->location) {
            $query->andWhere('p.location LIKE :location')
                ->setParameter('location', '%' . $photoFilterDto->location . '%');
        }

        if (NULL !== $photoFilterDto->camera) {
            $query->andWhere('p.camera LIKE :camera')
                ->setParameter('camera', '%' . $photoFilterDto->camera . '%');
        }

        if ($photoFilterDto->description) {
            $query->andWhere('p.description LIKE :description')
                ->setParameter('description', '%' . $photoFilterDto->description . '%');
        }

        if (NULL !== $photoFilterDto->username) {
            $query->andWhere('u.username LIKE :username')
                ->setParameter('username', '%' . $photoFilterDto->username . '%');
        }

        if ($photoFilterDto->takenAt) {
            $query->andWhere('p.takenAt >= :dateStart')
                ->andWhere('p.takenAt <= :dateEnd')
                ->setParameter('dateStart', $photoFilterDto->takenAt->format('Y-m-d 00:00:00'))
                ->setParameter('dateEnd', $photoFilterDto->takenAt->format('Y-m-d 23:59:59'));
        }

        $s = $query->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $s;
    }
}
