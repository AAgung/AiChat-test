<?php

use App\Models\PurchaseTransaction;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PurchaseTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate tables
        Schema::disableForeignKeyConstraints();
        DB::table(with(new PurchaseTransaction)->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        factory(PurchaseTransaction::class, 20)->create();

        // Create data that qualified to get voucher => customer id (1, 2)
        $faker = Faker::create();
        PurchaseTransaction::insert([
            [
                'customer_id' => 1,
                'total_spent' => 45,
                'total_saving' => 0,
                'transaction_at' => $faker->dateTimeBetween('-3 months', '-1 day'),
            ],
            [
                'customer_id' => 1,
                'total_spent' => 33.5,
                'total_saving' => 0,
                'transaction_at' => $faker->dateTimeBetween('-3 months', '-1 day'),
            ],
            [
                'customer_id' => 1,
                'total_spent' => 50,
                'total_saving' => 0,
                'transaction_at' => $faker->dateTimeBetween('-3 months', '-1 day'),
            ],
            [
                'customer_id' => 2,
                'total_spent' => 25,
                'total_saving' => 0,
                'transaction_at' => $faker->dateTimeBetween('-3 months', '-1 day'),
            ],
            [
                'customer_id' => 2,
                'total_spent' => 33.5,
                'total_saving' => 0,
                'transaction_at' => $faker->dateTimeBetween('-3 months', '-1 day'),
            ],
            [
                'customer_id' => 2,
                'total_spent' => 99,
                'total_saving' => 0,
                'transaction_at' => $faker->dateTimeBetween('-3 months', '-1 day'),
            ],
        ]);
    }
}
