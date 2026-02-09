<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query;

final class GetPhotoQuery
{
    public function __construct(
        public readonly int $id
    ){}
}
