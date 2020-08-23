<?php

namespace App\Domain\Exceptions;

use Throwable;

class TransactionDeniedException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(trans('exceptions.integrations.transaction_denied'), $code, $previous);
    }
}
