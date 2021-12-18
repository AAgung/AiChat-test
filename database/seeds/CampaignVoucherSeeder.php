<?php

use App\Models\master\CampaignVoucher;
use Illuminate\Database\Seeder;

class CampaignVoucherSeeder extends Seeder
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
        DB::table(with(new CampaignVoucher)->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        factory(CampaignVoucher::class, 1000)->create();
    }
}
