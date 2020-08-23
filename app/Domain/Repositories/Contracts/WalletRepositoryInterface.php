<?php

namespace App\Domain\Repositories\Contracts;

use App\Domain\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

interface WalletRepositoryInterface
{
    public function findByUserId(int $userId): ?Wallet;
    public function save(array $attributes): Model;
    public function update(Model $model, array $attributes): Model;
}
