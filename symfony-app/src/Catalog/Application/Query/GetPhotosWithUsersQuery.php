<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query;

use App\Shared\Application\Dto\PhotoFilterDto;

final class GetPhotosWithUsersQuery
{
    /**
     * @param PhotoFilterDto $criteria
     */
    public function __construct(
        public readonly PhotoFilterDto $criteria
    ){}
}
