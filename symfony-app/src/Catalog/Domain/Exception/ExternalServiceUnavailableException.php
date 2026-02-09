<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

use RuntimeException;

class ExternalServiceUnavailableException extends RuntimeException
{
    public function __construct(string $provider, ?\Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Usługa zewnętrzna "%s" jest obecnie nieosiągalna.', $provider),
            0,
            $previous
        );
    }
}
