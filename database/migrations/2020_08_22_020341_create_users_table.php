<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('cpf')->unique()->nullable();
            $table->string('cnpj')->unique()->nullable();
            $table->string('password');
            $table->integer('user_type_id');
            $table->timestamps();
            $table->foreign('user_type_id')->references('id')->on('user_types');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
