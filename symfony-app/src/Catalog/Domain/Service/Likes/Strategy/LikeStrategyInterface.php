<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Service\Likes\Strategy;

use App\Catalog\Domain\Entity\Photo;

interface LikeStrategyInterface
{
    /**
     * Wykonuje akcję like/unlike na zdjęciu
     *
     * @throws \App\Catalog\Application\Exception\LikeException
     */
    public function execute(Photo $photo): void;
}
