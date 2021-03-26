<?php

use App\Models\Company;
use App\Models\Faq;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Faq::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'created_by' => function () {
            return factory(User::class)->create()->id;
        },
        'question' => $faker->sentence(),
    ];
});
