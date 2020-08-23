<?php

namespace App\Domain\Repositories\Contracts;

use App\Domain\Models\Wallet;

interface WalletRepositoryInterface
{
    public function findByUserId(int $userId): ?Wallet;
}
