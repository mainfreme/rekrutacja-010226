<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Entity\Like;
use App\Catalog\Domain\Entity\Photo;
use App\Catalog\Domain\Repository\LikeRepositoryInterface;
use App\Identity\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineLikeRepository extends ServiceEntityRepository implements LikeRepositoryInterface
{
    private ?User $user;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    #[\Override]
    public function unlikePhoto(Photo $photo): void
    {
        $like = $this->createQueryBuilder('l')
            ->where('l.user = :user')
            ->andWhere('l.photo = :photo')
            ->setParameter('user', $this->user)
            ->setParameter('photo', $photo)
            ->getQuery()
            ->getOneOrNullResult();

        if ($like) {
            $em = $this->getEntityManager();
            $em->remove($like);
            $em->flush();
        }
    }

    #[\Override]
    public function hasUserLikedPhoto(Photo $photo): bool
    {
        $likes = $this->createQueryBuilder('l')
            ->select('l.id')
            ->where('l.user = :user')
            ->andWhere('l.photo = :photo')
            ->setParameter('user', $this->user)
            ->setParameter('photo', $photo)
            ->getQuery()
            ->getArrayResult();

        return count($likes) > 0;
    }

    #[\Override]
    public function createLike(Photo $photo): Like
    {
        $like = new Like();
        $like->setUser($this->user);
        $like->setPhoto($photo);

        $em = $this->getEntityManager();
        $em->persist($like);
        $em->flush();

        return $like;
    }

    #[\Override]
    public function updatePhotoCounter(Photo $photo, int $increment): void
    {
        $em = $this->getEntityManager();
        $photo->setLikeCounter($photo->getLikeCounter() + $increment);
        $em->persist($photo);
        $em->flush();
    }
}
