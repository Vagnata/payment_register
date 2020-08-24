<?php

namespace App\Domain\Observers;

use App\Domain\Models\Transaction;
use App\Domain\Services\NotificationService;

class TransactionObserver
{
    /** @var NotificationService */
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(Transaction $transaction): void
    {
        $this->notificationService->addNotification($transaction);
    }
}
