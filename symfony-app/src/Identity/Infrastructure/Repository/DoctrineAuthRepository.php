<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository;

use App\Identity\Domain\Entity\AuthToken;
use App\Identity\Domain\ValueObject\AuthToken as AuthTokenValueObject;
use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Repository\AuthRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Identity\Domain\ValueObject\Username;

class DoctrineAuthRepository extends ServiceEntityRepository implements AuthRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthToken::class);
    }

    public function findByToken(AuthTokenValueObject $token): ?AuthToken
    {
        return $this->createQueryBuilder('t')
            ->where('t.token = :token')
            ->setParameter('token', $token->getValue())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByUsername(Username $username): ?User
    {
        return $this->createQueryBuilder('t')
            ->where('t.username = :username')
            ->setParameter('username', $username->getValue())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findById(int $id): ?User
    {
        return $this->createQueryBuilder('t')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
