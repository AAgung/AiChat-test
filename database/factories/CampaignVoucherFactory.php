<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\master\CampaignVoucher;
use App\Models\master\Campaign;
use Faker\Generator as Faker;


$factory->define(CampaignVoucher::class, function (Faker $faker) {
    $campaignId = Campaign::select('id')->first()->id; 
    
    return [
        'campaign_id' => $campaignId,    
        'code' => DB::raw('LEFT(MD5(RAND()), 7)'), 
        'value' => 10, 
    ];
});
