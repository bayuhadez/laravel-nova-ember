<?php

use App\Models\SeminarProductMeta;
use App\Models\SeminarProductSponsor;
use Faker\Generator as Faker;

$factory->define(SeminarProductSponsor::class, function (Faker $faker) {
    return [
        'sponsor_name' => $faker->name,
        'seminar_product_meta_id' => function () {
            return factory(SeminarProductMeta::class)->create()->id;
        },
    ];
});
