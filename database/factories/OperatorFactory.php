<?php

$factory->define(MMC\Operator::class, function (Faker\Generator $faker) {
    return [
        'login' => $faker->name,
        'password' => bcrypt('secret'),
        'name' => $faker->name,
        'remember_token' => str_random(10),
    ];
});
