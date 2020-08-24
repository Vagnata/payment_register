<?php

namespace App\Domain\Exceptions;

use Throwable;

class InvalidUserTypeException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(trans('exceptions.user.transfer.invalid_type'), $code, $previous);
    }
}
