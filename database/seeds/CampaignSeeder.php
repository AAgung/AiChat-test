<?php

use App\Models\master\Campaign;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
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
        DB::table(with(new Campaign)->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $faker = Faker::create();
        $campaign = Campaign::create([
            'name' => 'Campaign A',
            'slug' => Str::slug('Campaign A'),
            'start_at' => $faker->dateTime(),
            'end_at' => $faker->dateTimeBetween('+2 weeks', '+1 month')
        ]);
    }
}
