<?php

namespace App\Domain\Services;

use App\Domain\Enuns\NotificationStatusEnum;
use App\Domain\Models\Notification;
use App\Domain\Models\Transaction;
use App\Domain\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    private $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function addNotification(Transaction $transaction): Notification
    {
        $data = [
            'user_id'             => $transaction->payeeWallet->user->id,
            'message'             => trans(
                'messages.notification.transaction_successfully',
                ['value' => number_format($transaction->amount, 2)]
            ),
            'notification_status' => NotificationStatusEnum::AWAITING
        ];

        return $this->notificationRepository->save($data);
    }

    public function findAwaitingNotifications(): Collection
    {
        return $this->notificationRepository->findAwaitingNotifications();
    }

    public function updateNotification(Notification $notification, array $data): Notification
    {
        return $this->notificationRepository->update($notification, $data);
    }
}
