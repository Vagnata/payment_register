<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Enuns\NotificationStatusEnum;
use App\Domain\Models\Notification;
use App\Domain\Models\User;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    $user = User::first() ?? factory(User::class)->create();
    return [
        'user_id'             => $user->id,
        'message'             => $faker->text,
        'notification_status' => $faker->randomElement(NotificationStatusEnum::toArray())
    ];
});
