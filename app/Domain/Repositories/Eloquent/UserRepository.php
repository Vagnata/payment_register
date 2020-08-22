<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Models\User;
use App\Domain\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function save(array $userData): User
    {
        $user = new User();
        $user->fill($userData);
        $user->save();

        return $user;
    }

    public function findById(int $userId): ?User
    {
        $user = new User();
        $user->where('id', $userId);

        return $user->first();
    }
}
