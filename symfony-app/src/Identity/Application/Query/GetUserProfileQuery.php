<?php

declare(strict_types=1);

namespace App\Identity\Application\Query;

final class GetUserProfileQuery
{
    public function __construct(
        public readonly int $id
    ) {}
}
