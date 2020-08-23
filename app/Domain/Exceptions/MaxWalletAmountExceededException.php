<?php

namespace App\Domain\Exceptions;

use Throwable;

class MaxWalletAmountExceededException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = empty($message) ? trans('exceptions.wallet.limit_exceeded') : $message;

        parent::__construct($message, $code, $previous);
    }
}
