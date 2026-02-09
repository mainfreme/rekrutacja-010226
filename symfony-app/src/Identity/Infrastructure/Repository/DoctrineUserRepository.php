<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\ValueObject\AuthToken as TokenVO;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\Username;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    #[\Override]
    public function findByUsername(Username $username): ?User
    {
        return $this->findOneBy(['username' => $username]);
    }

    #[\Override]
    public function findByEmail(Email $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    #[\Override]
    public function findById(int $id): ?User
    {
        return $this->find($id);
    }

    public function updateToken(int $id, TokenVO $token): ?User
    {
        $user = $this->findById($id);
        if ($user) {
            $user->setToken($token->getValue());
            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();

            return $user;
        }

        return null;
    }

}
