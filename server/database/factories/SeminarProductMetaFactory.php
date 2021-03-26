<?php

use App\Models\Product;
use App\Models\SeminarProductMeta;
use Faker\Generator as Faker;

$factory->define(SeminarProductMeta::class, function (Faker $faker) {
    return [
        'is_session_in_progress' => false,
        'product_id' => function () {
            return factory(Product::class)->create()->id;
        },
        'is_past' => false,
    ];
});
