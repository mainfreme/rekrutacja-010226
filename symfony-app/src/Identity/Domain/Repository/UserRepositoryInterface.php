<?php

declare(strict_types=1);

namespace App\Identity\Domain\Repository;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\Username;
use App\Identity\Domain\ValueObject\AuthToken as TokenVO;

interface UserRepositoryInterface
{
    public function findByUsername(Username $username): ?User;

    public function findByEmail(Email $email): ?User;

    public function findById(int $id): ?User;

    public function updateToken(int $id, TokenVO $token): ?User;
}
