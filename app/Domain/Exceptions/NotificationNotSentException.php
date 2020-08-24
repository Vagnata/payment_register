<?php

namespace App\Domain\Exceptions;

use Throwable;

class NotificationNotSentException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(trans('exceptions.integrations.notification_not_sent'), $code, $previous);
    }
}
