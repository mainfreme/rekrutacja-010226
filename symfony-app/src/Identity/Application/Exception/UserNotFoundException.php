<?php

declare(strict_types=1);

namespace App\Identity\Application\Exception;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message = "Użytkownik nie został odnaleziony", int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
