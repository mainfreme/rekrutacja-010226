<?php

namespace App\Catalog\Domain\Service\Import;

use App\Identity\Domain\Entity\User;

interface ImportStrategyInterface
{
    public function import(User $user): array;

    public function supports(string $source): bool;
}
