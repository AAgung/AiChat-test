<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Model;

class CampaignVoucher extends Model
{
    protected $table = 'm_campaign_voucher';

    protected $fillable = [
        'id',
        'campaign_id',
        'code',
        'value',
        'customer_id',
        'lockdown_at',
        'lockdown_expired_at'
    ];

    public function campaign()
    {
        return $this->belongsTo('App\Models\master\Campaign');
    }

    public function customer()
    {
        return $this->belongsTo('App\models\master\Customer');
    }
}
