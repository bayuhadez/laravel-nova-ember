<?php

use App\Models\Company;
use App\Models\ProductCategory;
use Faker\Generator as Faker;

$factory->define(ProductCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
    ];
});
