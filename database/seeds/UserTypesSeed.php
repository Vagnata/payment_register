<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypesSeed extends Seeder
{
    public function run()
    {
        DB::table('user_types')->insertOrIgnore(['id' => 1, 'name' => 'Comum']);
        DB::table('user_types')->insertOrIgnore(['id' => 2, 'name' => 'Lojista']);
    }
}
