<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Enuns\TransactionStatusEnum;
use App\Domain\Models\Transaction;
use App\Domain\Models\Wallet;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    $payerWallet = Wallet::first() ?? factory(Wallet::class)->create();
    $payeeWallet = Wallet::all()->last() ?? factory(Wallet::class)->create();
    return [
        'payer_wallet_id'       => $payerWallet->id,
        'payee_wallet_id'       => $payeeWallet->id,
        'amount'                => 100.00,
        'transaction_status_id' => $faker->randomElement(TransactionStatusEnum::toArray())
    ];
});
