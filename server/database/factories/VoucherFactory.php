<?php

use App\Models\Company;
use App\Models\Voucher;
use Faker\Generator as Faker;

$factory->define(Voucher::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'name' => $faker->name,
        'stock' => $faker->randomFloat(2, 2, 999999),
        'amount' => $faker->randomFloat(2, 2, 999999),
    ];
});
