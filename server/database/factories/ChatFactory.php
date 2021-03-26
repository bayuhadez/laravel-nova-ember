<?php

use App\Models\Chat;
use App\Models\ChatRoom;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Chat::class, function (Faker $faker) {
    return [
        'sender_user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'chat_room_id' => function () {
            return factory(ChatRoom::class)->create()->id;
        },
        'message' => $faker->sentence(),
    ];
});
