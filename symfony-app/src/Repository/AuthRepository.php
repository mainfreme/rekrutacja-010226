<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AuthToken;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthToken::class);
    }

    public function findByToken(string $token): ?AuthToken
    {
        return $this->createQueryBuilder('t')
            ->where('t.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUserByUsername(string $username): ?User
    {
        return $this->getEntityManager()
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
    }
}
