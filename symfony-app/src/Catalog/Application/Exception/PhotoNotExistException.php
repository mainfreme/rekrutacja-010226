<?php

declare(strict_types=1);

namespace App\Catalog\Application\Exception;

class PhotoNotExistException extends \Exception
{
    public function __construct(string $message = "Nie odnaleziono zdjęcia", int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
