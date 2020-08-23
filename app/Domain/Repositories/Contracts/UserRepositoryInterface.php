<?php

namespace App\Domain\Repositories\Contracts;

use App\Domain\Models\User;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function findById(int $userId): ?User;
    public function save(array $attributes): Model;
    public function update(Model $model, array $attributes): Model;
}
