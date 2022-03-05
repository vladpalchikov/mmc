<?php

$factory->define(MMC\Models\Service::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->realText,
        'service_included' => $faker->realText,
        'price' => $faker->randomFloat(2, 1000, 5000),
    ];
});
