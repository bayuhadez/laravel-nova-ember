<?php

use App\Models\Company;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'voucher_id' => function () {
            return factory(Voucher::class)->create()->id;
        },
        'snap_transaction_token' => $faker->regexify('([a-zA-Z0-9_-]){36,48}'),
    ];
});
