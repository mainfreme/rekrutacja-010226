<?php

declare(strict_types=1);

namespace App\Catalog\Application\Command;

use App\Identity\Domain\Entity\User;

final class ImportDataCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly string $provider
    )
    {}
}
