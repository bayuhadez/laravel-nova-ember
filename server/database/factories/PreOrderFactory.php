<?php

use App\Models\PreOrder;
use Faker\Generator as Faker;

$factory->define(PreOrder::class, function (Faker $faker) {
    return [
        'number' => "FPO".date('YmdHis').$faker->numberBetween(100,999),
        'ordered_at' => Carbon::now()->toDateTimeString(),
        'due_at' => Carbon::now()->addWeek()->toDateTimeString(),
        'status' => PreOrder::STATUS_NOT_DONE,
        'rounding_value' => null,
        'rounding_type' => null,
        'is_ppn' => true,
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'supplier_id' => function () {
            return factory(Supplier::class)->create()->id;
        },
        'created_by' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
