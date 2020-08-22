<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Models\Wallet;
use App\Domain\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function save(array $userData): Wallet
    {
        $user = new Wallet();
        $user->fill($userData);
        $user->save();

        return $user;
    }
}
