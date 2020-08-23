<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Enuns\TransactionStatusEnum;
use App\Domain\Models\Transaction;
use App\Domain\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository extends AbstractRepository implements TransactionRepositoryInterface
{
    protected $model = Transaction::class;

    public function findInProgressTransactions(int $userId): Collection
    {
        $transaction = new Transaction();

        return $transaction
            ->select([
                'transactions.id',
                'payer_user.id as payer_user_id',
                'payee_user.id as payee_user_id'
            ])
            ->leftJoin('wallets as payer_wallet', 'payer_wallet.id', 'transactions.payer_wallet_id')
            ->leftJoin('wallets as payee_wallet', 'payee_wallet.id', 'transactions.payee_wallet_id')
            ->leftJoin('users as payer_user', 'payer_user.id', 'payer_wallet.user_id')
            ->leftJoin('users as payee_user', 'payee_user.id', 'payee_wallet.user_id')
            ->where('transactions.transaction_status_id', TransactionStatusEnum::IN_PROGRESS)
            ->where(function ($userClause) use ($userId) {
                $userClause->where('payer_user.id', $userId)
                    ->orWhere('payee_user.id', $userId);
            })
            ->get();
    }
}
