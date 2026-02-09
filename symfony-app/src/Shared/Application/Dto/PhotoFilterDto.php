<?php

declare(strict_types=1);

namespace App\Shared\Application\Dto;

use DateTimeInterface;
use Symfony\Component\Form\FormInterface;

class PhotoFilterDto
{
    public function __construct(
        public ?string $location = null,
        public ?string $camera = null,
        public ?string $description = null,
        public ?string $username = null,
        public ?DateTimeInterface $takenAt = null,
    ){}
}
