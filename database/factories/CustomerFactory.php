<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\master\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    $gender = $faker->randomElement([Customer::GENDER_MALE, Customer::GENDER_FEMALE]);

    return [
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName($gender),
        'gender' => $gender,
        'date_of_birth' => $faker->dateTimeBetween('-40 years', '-20 years'),
        'contact_number' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
    ];
});
