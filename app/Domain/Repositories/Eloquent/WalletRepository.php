<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Models\Wallet;
use App\Domain\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository extends AbstractRepository implements WalletRepositoryInterface
{
    protected $model = Wallet::class;

    public function findByUserId(int $userId): ?Wallet
    {
        $wallet = new Wallet();

        return $wallet->where('user_id', $userId)
            ->first();
    }
}
