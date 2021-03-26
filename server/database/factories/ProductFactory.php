<?php

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Product::class, function (Faker $faker, array $options = []) {
    return [
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'name' => $faker->name,
        'price' => 0,
        'product_category_id' => function () {
            return factory(ProductCategory::class)->create()->id;
        },
        'status' => 1,
    ];
});
