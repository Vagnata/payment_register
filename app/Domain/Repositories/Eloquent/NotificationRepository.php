<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Models\Transaction;
use App\Domain\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository extends AbstractRepository implements NotificationRepositoryInterface
{
    protected $model = Transaction::class;

    public function findAwaitingNotifications(): Collection
    {
        // TODO: Implement findAwaitingNotifications() method.
    }
}
