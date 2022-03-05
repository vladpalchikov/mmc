<?php

$factory->define(MMC\Models\Application::class, function (Faker\Generator $faker) {
    return [
        'surname' => $faker->lastName,
		'name' => $faker->firstName,
		'middle_name' => $faker->firstName,
		'birthday'  => $faker->date,
		'nationality' => 'Русский',
		'registration_date' => $faker->date,
		'document_name' => $faker->word,
		'document_number' => 1,
		'document_date' => $faker->date,
		'address' => $faker->address,
		'phone' => $faker->e164PhoneNumber,
		'operator_id' => 6
    ];
});
