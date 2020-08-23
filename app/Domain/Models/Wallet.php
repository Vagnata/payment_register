<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed amount
 */
class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
