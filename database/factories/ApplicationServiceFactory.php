<?php

$factory->define(MMC\Models\ApplicationService::class, function (Faker\Generator $faker) {
	$service = MMC\Models\Service::all()->random();
	
    return [
        'application_id' => $faker->numberBetween(1, 5),
		'service_id' => $service->id,
		'service_description' => $service->description,
		'service_price' => $service->price,
		'service_name' => $service->name
    ];
});
