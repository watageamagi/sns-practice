<?php

use Faker\Generator as Faker;
use App\Models\Admin;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'password' => $faker->password
    ];
});
