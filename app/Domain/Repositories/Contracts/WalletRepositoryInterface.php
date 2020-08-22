<?php

namespace App\Domain\Repositories\Contracts;

use App\Domain\Models\Wallet;

interface WalletRepositoryInterface
{
    public function save(array $walletData): Wallet;
}
