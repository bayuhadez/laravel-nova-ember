<?php

use App\Models\Product;
use App\Models\ProductBanner;
use Faker\Generator as Faker;

$factory->define(ProductBanner::class, function (Faker $faker) {
    return [
        'banner_name' => $faker->name,
        'banner_image_path' => $faker->sha1.'.jpeg',
        'product_id' => function () {
            return factory(Product::class)->create()->id;
        },
    ];
});
