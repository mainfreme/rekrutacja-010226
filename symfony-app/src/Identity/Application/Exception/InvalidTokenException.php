<?php

declare(strict_types=1);

namespace App\Identity\Application\Exception;



class InvalidTokenException extends \Exception
{
    public function __construct(string $message = "Podany token jest nie prawidłowy", int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
