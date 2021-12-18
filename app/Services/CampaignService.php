<?php

namespace App\Services;

use App\Models\master\Campaign;
use App\Models\master\CampaignVoucher;
use Illuminate\Http\Request;

class CampaignService
{
    /**
     * check accessable campaign due condition
     * condition 1: campaign end at still under current datetime
     * condition 2: campaign voucher not locked down by customer or not qualified by upload photo yet
     * 
     * @param string $campaignSlug
     * @return array
     */
    public function checkAccessable($campaignSlug = '')
    {
        // check data campaign is exists filtered by campaign_slug
        $campaign = Campaign::select('id')
            ->where('slug', $campaignSlug)
            ->whereRaw('UNIX_TIMESTAMP(NOW()) <= UNIX_TIMESTAMP(end_at)')
            ->whereRaw('EXISTS (SELECT id FROM '.with(new CampaignVoucher)->getTable().' WHERE campaign_id = '.with(new Campaign)->getTable().'.id AND (is_qualified = ? OR customer_id IS NULL))', 0)
            ->first();

        if(!$campaign) {
            return [
                'success' => false,
                'message' => 'Campaign data not found',
                'data' => [],
            ]; 
        }

        return [
            'success' => true,
            'data' => $campaign,
            'message' => 'Campaign still accessable'
        ];
    }
}