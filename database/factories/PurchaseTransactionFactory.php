<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PurchaseTransaction;
use App\Models\master\Customer;
use Faker\Generator as Faker;

$factory->define(PurchaseTransaction::class, function (Faker $faker) {
    $customer = $faker->randomElement(Customer::select('id')->whereNotIn('id', [1, 2])->get());
    return [
        'customer_id' => $customer->id,
        'total_spent' => $faker->randomNumber(2),
        'total_saving' => 0,
        'transaction_at' => $faker->dateTimeBetween('-3 months', '-1 day'),
    ];
});
