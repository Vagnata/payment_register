<?php

namespace App\Domain\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TransactionRepositoryInterface
{
    public function findInProgressTransactions(int $userId): Collection;
    public function save(array $attributes): Model;
    public function update(Model $model, array $attributes): Model;
}
