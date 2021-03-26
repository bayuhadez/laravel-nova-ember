<?php

use App\Models\MidtransTransaction;
use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(MidtransTransaction::class, function (Faker $faker) {
    return [
        'order_id' => function () {
            return factory(Order::class)->create()->id;
        },
        'order_number' => config('app.payment_id_prefix').$faker->unique()->randomDigit,
        'payment_type' => $faker->randomElement([
            'bank_transfer',
            'bca_klikbca',
            'bca_klikpay',
            'credit_card',
            'cstore',
            'echannel',
            'gopay',
            'mandiri_clickpay',
        ]),
    ];
});
