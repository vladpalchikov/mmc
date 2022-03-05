<?php

$factory->define(MMC\Admin::class, function (Faker\Generator $faker) {
    return [
        'login' => $faker->name,
        'password' => 'secret',
        'remember_token' => str_random(10),
    ];
});
