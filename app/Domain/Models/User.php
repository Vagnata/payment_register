<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class User extends Model
{
    protected $fillable = [
        'name', 'email', 'cpf', 'cnpj', 'password', 'user_type_id'
    ];

    protected $hidden = [
        'password'
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }
}
