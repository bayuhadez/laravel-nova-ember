<?php

use App\Models\Banner;
use App\Models\Company;
use App\Models\Product;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(Banner::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'image' => uniqid().'.jpg',
    ];
});

$factory->state(Banner::class, 'image', function ($faker) {

    Storage::fake(config('filesystem.testing'));

    $file = UploadedFile::fake()->image(uniqid().'.jpg');

    return ['image' => $file];
});
