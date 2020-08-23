<?php

namespace App\Domain\Exceptions;

use Throwable;

class WalletNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(trans('exceptions.wallet.not_found'), $code, $previous);
    }
}
