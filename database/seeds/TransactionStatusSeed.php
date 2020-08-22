<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionStatusSeed extends Seeder
{
    public function run()
    {
        DB::table('transaction_status')->insertOrIgnore(['id' => 1, 'name' => 'Em Andamento']);
        DB::table('transaction_status')->insertOrIgnore(['id' => 2, 'name' => 'Finalizada']);
        DB::table('transaction_status')->insertOrIgnore(['id' => 3, 'name' => 'Cancelada']);
    }
}
