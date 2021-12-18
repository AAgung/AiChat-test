<?php

use App\Models\master\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
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
        DB::table(with(new Customer)->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        factory(Customer::class, 10)->create();
    }
}
