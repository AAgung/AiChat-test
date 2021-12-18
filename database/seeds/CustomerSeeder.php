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

        // Update email for test purposes only
        Customer::where('id', 1)
            ->update([
                'email' => 'test@aichat.id'
            ]);
    }
}
