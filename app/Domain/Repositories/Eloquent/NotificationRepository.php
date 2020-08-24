<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Enuns\NotificationStatusEnum;
use App\Domain\Models\Notification;
use App\Domain\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository extends AbstractRepository implements NotificationRepositoryInterface
{
    protected $model = Notification::class;

    public function findAwaitingNotifications(): Collection
    {
        $notification = new Notification();

        return $notification
            ->where('notification_status', NotificationStatusEnum::AWAITING)
            ->get();
    }
}
