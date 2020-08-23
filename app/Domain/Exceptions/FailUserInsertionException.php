<?php

namespace App\Domain\Exceptions;

use Throwable;

class FailUserInsertionException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(trans('exceptions.user.insertion_failure'), $code, $previous);
    }
}
