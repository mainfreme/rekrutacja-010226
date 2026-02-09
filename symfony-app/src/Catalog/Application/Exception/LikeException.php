<?php

declare(strict_types=1);

namespace App\Catalog\Application\Exception;

class LikeException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
