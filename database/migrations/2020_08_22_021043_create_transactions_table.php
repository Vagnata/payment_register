<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payer_wallet_id');
            $table->unsignedBigInteger('payee_wallet_id');
            $table->decimal('amount');
            $table->unsignedBigInteger('transaction_status_id');
            $table->timestamps();
            $table->foreign('payer_wallet_id')->references('id')->on('wallets');
            $table->foreign('payee_wallet_id')->references('id')->on('wallets');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
