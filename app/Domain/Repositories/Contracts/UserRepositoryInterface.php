<?php

namespace App\Domain\Repositories\Contracts;

use App\Domain\Models\User;

interface UserRepositoryInterface
{
    public function save(array $userData): User;
    public function findById(int $userId);
}
