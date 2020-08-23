<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Models\Wallet;
use App\Domain\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function save(array $walletData): Wallet
    {
        $wallet = new Wallet();
        $wallet->fill($walletData);
        $wallet->save();

        return $wallet;
    }

    public function findByUserId(int $userId): ?Wallet
    {
        $wallet = new Wallet();

        return $wallet->where('user_id', $userId)
            ->first();
    }

    public function update(Wallet $wallet, array $attributes): Wallet
    {
        $wallet->update($attributes);

        return $wallet;
    }
}
