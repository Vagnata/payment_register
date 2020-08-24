<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 * @property Wallet payeeWallet
 */
class Transaction extends Model
{
    protected $fillable = [
        'payer_wallet_id', 'payee_wallet_id', 'amount', 'transaction_status_id'
    ];

    public function payeeWallet()
    {
        return $this->belongsTo(Wallet::class, 'payee_wallet_id');
    }
}
