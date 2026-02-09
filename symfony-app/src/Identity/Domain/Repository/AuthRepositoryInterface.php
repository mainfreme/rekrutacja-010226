<?php

declare(strict_types=1);

namespace App\Identity\Domain\Repository;

use App\Identity\Domain\Entity\AuthToken;
use App\Identity\Domain\ValueObject\AuthToken as AuthTokenValueObject;
use App\Identity\Domain\Entity\User;
use App\Identity\Domain\ValueObject\Username;

interface AuthRepositoryInterface
{
    public function findByToken(AuthTokenValueObject $token): ?AuthToken;

    public function findByUsername(Username $username): ?User;

    public function findById(int $id): ?User;
}