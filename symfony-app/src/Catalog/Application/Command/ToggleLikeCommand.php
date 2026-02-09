<?php

declare(strict_types=1);

namespace App\Catalog\Application\Command;

/**
 * Command to toggle like on a photo (add or remove)
 */
final class ToggleLikeCommand
{
    public function __construct(
        public readonly int $photoId,
        public readonly int $userId,
    ) {}
}
