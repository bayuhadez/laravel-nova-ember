<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\Phone;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('[A-Za-z0-9]{5,8}'),
        'email' => $faker->email,
        'bill_type' => Customer::BILL_TYPE_MAIN_CUSTOMER,
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'telephone_id' => function () {
            return factory(Phone::class)->create()->id;
        },
    ];
});
