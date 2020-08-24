<?php

namespace App\Domain\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface NotificationRepositoryInterface
{
    public function findAwaitingNotifications(): Collection;
    public function save(array $attributes): Model;
    public function update(Model $model, array $attributes): Model;
}
