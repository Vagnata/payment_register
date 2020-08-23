<?php

namespace App\Domain\Repositories\Contracts;

use App\Domain\Models\Wallet;

interface WalletRepositoryInterface
{
    public function save(array $walletData): Wallet;
    public function findByUserId(int $userId): ?Wallet;
    public function update(Wallet $wallet, array $attributes): Wallet;
}
