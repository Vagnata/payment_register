<?php

namespace App\Domain\Enuns;

use BenSampo\Enum\Enum;

class TransactionStatusEnum extends Enum
{
    const IN_PROGRESS = 1;
    const FINALIZED   = 2;
    const CANCELED    = 3;
}
