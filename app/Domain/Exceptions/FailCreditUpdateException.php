<?php

namespace App\Domain\Exceptions;

use Throwable;

class FailCreditUpdateException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = empty($message) ? trans('exceptions.wallet.credit_update_failure') : $message;

        parent::__construct($message, $code, $previous);
    }
}
