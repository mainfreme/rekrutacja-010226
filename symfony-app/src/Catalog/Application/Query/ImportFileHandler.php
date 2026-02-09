<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query;

use App\Catalog\Domain\Repository\PhotoRepositoryInterface;

final class ImportFileHandler
{
    public function __construct(
        private PhotoRepositoryInterface $photoRepository
    )
    {
    }

    public function __invoke()
    {

    }

    private function add()
    {

    }
}
