<?php

namespace App\Domain\Exceptions;

use Throwable;

class UserAlreadyInATransactionException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
