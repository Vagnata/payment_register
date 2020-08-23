<?php

namespace App\Domain\Repositories\Contracts;

use App\Domain\Models\User;

interface UserRepositoryInterface
{
    public function findById(int $userId): ?User;
}
