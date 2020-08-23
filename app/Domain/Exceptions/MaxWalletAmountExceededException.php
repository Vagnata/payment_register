<?php

namespace App\Domain\Exceptions;

use Throwable;

class MaxWalletAmountExceededException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(trans('exceptions.wallet.limit_exceeded'), $code, $previous);
    }
}
