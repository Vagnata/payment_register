<?php

namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name', 'email', 'cpf', 'cnpj', 'password', 'user_type_id'
    ];

    protected $hidden = [
        'password'
    ];
}
