<?php

use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'iso' => $faker->unique()->regexify('[A-Za-z0-9]{2}'),
        'name' => strtoupper($faker->name),
        'nicename' => $faker->name,
        'iso3' => $faker->regexify('[A-Za-z0-9]{3}'),
        'numcode' => $faker->regexify('[0-9]{3}'),
        'phonecode' => $faker->regexify('[0-9]{3}'),
    ];
});
