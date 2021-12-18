<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'm_campaign';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'start_at',
        'end_at'
    ];

    public function campaign_voucher()
    {
        return $this->hasMany('App\Models\master\CampaignVoucher');
    }
}
