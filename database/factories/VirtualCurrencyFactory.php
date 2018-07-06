<?php

use Faker\Generator as Faker;

$factory->define(App\VirtualCurrency::class, function (Faker $faker) {
    return [
        'source_user_id' => 1,
        'destination_user_id' => 2,
        'credit' => 0.25
    ];
});
