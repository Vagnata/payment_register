<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Enuns\UserTypesEnum;
use App\Domain\Models\User;
use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Company;
use Faker\Provider\pt_BR\Internet;
use Faker\Provider\pt_BR\Person;

$factory->define(User::class, function (Faker $faker) {
    $faker->addProvider(new Person($this->faker));
    $faker->addProvider(new Internet($this->faker));
    $faker->addProvider(new Company($this->faker));

    return [
        'name'         => $this->faker->name,
        'cpf'          => $this->faker->cpf(false),
        'email'        => $this->faker->email,
        'cnpj'         => $this->faker->cnpj(false),
        'password'     => $this->faker->password,
        'user_type_id' => $this->faker->randomElement(UserTypesEnum::toArray())
    ];
});
