<?php

namespace App\Domain\Enuns;

use BenSampo\Enum\Enum;

class NotificationStatusEnum extends Enum
{
    const AWAITING = 'AWAITING';
    const SENT     = 'SENT';
}
