<?php

use App\Models\Country;
use App\Models\Phone;
use Faker\Generator as Faker;

$factory->define(Phone::class, function (Faker $faker) {
    return [
        'country_id' => function () {
            return config('app.default_country_id') ?? factory(Country::class)->create()->id;
        },
        'number' => $faker->regexify('[0-9]{20}'),
    ];
});
