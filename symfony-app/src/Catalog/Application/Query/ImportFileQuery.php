<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query;

use App\Identity\Domain\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImportFileQuery
{
    public function __construct(
        private readonly User $userModel
    ) {}
}
