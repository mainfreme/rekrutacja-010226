<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query;

use App\Catalog\Domain\Entity\Photo;

/**
 * Query to get map of user likes for given photos
 */
final class GetUserLikesQuery
{
    /**
     * @param int $userId
     * @param Photo[] $photos
     */
    public function __construct(
        public readonly int $userId,
        public readonly array $photos,
    ) {}
}
