<?php

namespace App\Services;

use App\Models\master\Campaign;
use App\Models\master\CampaignVoucher;
use Illuminate\Http\Request;

class CampaignService
{
    /**
     * check accessable campaign due condition
     * condition 1: current datetime still under or same with campaign end at 
     * condition 2: campaign voucher not locked down by customer or not qualified by upload photo yet
     * 
     * @param int $campaignId
     * @return array
     */
    public function checkAccessable($campaignId = 0)
    {
        // check data campaign is exists filtered by campaign_slug
        $campaign = Campaign::select('id')
            ->where('id', $campaignId)
            ->whereRaw('UNIX_TIMESTAMP(NOW()) <= UNIX_TIMESTAMP(end_at)')
            ->whereRaw('EXISTS (SELECT id FROM '.with(new CampaignVoucher)->getTable().' WHERE campaign_id = '.with(new Campaign)->getTable().'.id AND (customer_id IS NULL OR (is_qualified = ? AND lockdown_expired_at IS NOT NULL AND UNIX_TIMESTAMP(NOW()) > UNIX_TIMESTAMP(lockdown_expired_at))))', 0)
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