<?php

use App\Models\Sponsor;
use Faker\Generator as Faker;

$factory->define(Sponsor::class, function (Faker $faker) {
    return [
        'sponsor_name' => $faker->name,
        'sponsor_image_path' => $faker->sha1.'jpg',
        'platinum' => false,
    ];
});
