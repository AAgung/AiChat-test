<?php

namespace App\Services;

use App\Models\master\CampaignVoucher;
use App\Models\master\Customer;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class CampaignVoucherService
{
    /**
     * set locked down to selected customer with condition
     * condition 1: customer not have qualified campaign voucher or have locked down voucher that still active
     * condition 2: current time bigger or same than lockdown_expired_at 
     * 
     * @param string $customerEmail
     * @param int $campaignId
     * @return array
     */
    public function setLockDownToCustomer($customerEmail = '', $campaignId = 0)
    {
        $this->removeLockDownNotRedeem($campaignId);

        // check data customer is exists filtered by customer_email
        $customer = Customer::select('id')->where('email', $customerEmail)->first();
        if(!$customer) {
            return [
                'success' => false,
                'message' => 'Customer data not found',
                'data' => [],
            ]; 
        }

        // check campaign voucher for selected customer
        $campaignVoucher = CampaignVoucher::select('lockdown_expired_at')
            ->where('campaign_id', $campaignId)
            ->where('customer_id', $customer->id)
            ->where(function($query) {
                $query->where('is_qualified', 1)
                ->orWhereRaw('UNIX_TIMESTAMP(NOW()) <= UNIX_TIMESTAMP(lockdown_expired_at)');
            })->first();
        if($campaignVoucher) {
            return [
                'success' => false,
                'data' => $campaignVoucher,
                'message' => 'Customer has been get voucher or still have active voucher that not qualified by uploaded photo yet'
            ];    
        }
        
        // set lockdown to campaign voucher for selected customer
        $campaignVoucher = CampaignVoucher::where('id', function($query) use ($campaignId) {
            $query->select('id')
                ->from(DB::raw('(SELECT id from '.with(new CampaignVoucher)->getTable().' WHERE campaign_id = '.$campaignId.' AND customer_id IS NULL ORDER BY ID ASC LIMIT 1) AS cv'));
        })->update([
            'customer_id' => $customer->id,
            'lockdown_at' => Carbon::now(),
            'lockdown_expired_at' => Carbon::now()->addMinutes(10),
        ]);

        return [
            'success' => true,
            'data' => $campaignVoucher,
            'message' => 'Voucher has been locked down'
        ];
    }

    /**
     * remove lockedown from customer with condition
     * condition 1: campaign voucher not qualified by upload photo yet
     * condition 2: current time bigger or same than lockdown_expired_at 
     * 
     * @param int $campaignId
     * @return array
     */
    public function removeLockDownNotRedeem($campaignId = 0)
    {
        CampaignVoucher::where('campaign_id', $campaignId)
            ->where('is_qualified', 0)
            ->whereRaw('UNIX_TIMESTAMP(NOW()) > UNIX_TIMESTAMP(lockdown_expired_at)')
            ->whereNotNull('customer_id')
            ->update([
                'customer_id' => null,
                'lockdown_at' => null,
                'lockdown_expired_at' => null,
            ]);
    }
}