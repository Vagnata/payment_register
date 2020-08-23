<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Models\User;
use App\Domain\Models\Wallet;
use Faker\Generator as Faker;

$factory->define(Wallet::class, function (Faker $faker) {
    $user = User::first() ?? factory(User::class)->create();
    return [
        'user_id' => $user->id,
        'amount'  => 0.00
    ];
});
