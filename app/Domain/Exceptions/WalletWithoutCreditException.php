<?php

namespace App\Domain\Exceptions;

use Throwable;

class WalletWithoutCreditException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(trans('exceptions.wallet.transfer.without_credits'), $code, $previous);
    }
}
