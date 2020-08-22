<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionStatusSeed extends Seeder
{
    public function run()
    {
        DB::table('transaction_status')->insert(['id' => 1, 'name' => 'Em Andamento']);
        DB::table('transaction_status')->insert(['id' => 2, 'name' => 'Finalizada']);
        DB::table('transaction_status')->insert(['id' => 3, 'name' => 'Cancelada']);
    }
}
