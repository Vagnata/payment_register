<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed amount
 */
class Wallet extends Model
{
    const MAX_AMOUNT = 200000.00;

    protected $fillable = [
        'user_id', 'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
