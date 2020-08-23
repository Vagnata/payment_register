<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Models\User;
use App\Domain\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected $model = User::class;

    public function findById(int $userId): ?User
    {
        $user = new User();

        return $user->where('id', $userId)
            ->first();
    }
}
