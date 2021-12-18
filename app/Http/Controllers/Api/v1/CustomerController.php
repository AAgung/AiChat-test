<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Models\master\Campaign;
use App\Models\master\Customer;
use App\Models\PurchaseTransaction;
use App\Services\CampaignService;
use App\Services\CampaignVoucherService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    protected $campaignService,
        $campaignVoucherService;

    public function __construct(CampaignService $campaignService, CampaignVoucherService $campaignVoucherService)
    {
        $this->campaignService = $campaignService;
        $this->campaignVoucherService = $campaignVoucherService;
    }

    /**
     * check eligible customer to get campaign voucher 
     * 
     * @param \Illuminate\Http\Request (string customer_email, string campaign_slug)
     * @return \Illuminate\Http\Response
     */
    public function checkEligibleCampaignVoucher(Request $request)
    {
        // check request param campaign_slug is filled
        $campaignSlug = $request->campaign_slug ?? '';
        if(!$campaignSlug) return $this->sendError('Campaign slug not found', [], 400);

        // check data campaign is exists filtered by campaign_slug
        $campaign = Campaign::select('id')->where('slug', $campaignSlug)->first();
        if(!$campaign) return $this->sendError('Campaign data not found', [], 400);

        // check request param customer_email is filled
        $customerEmail = $request->customer_email ?? '';
        if(!$customerEmail) return $this->sendError('Customer email not found', [], 400);

        // check data customer is exists filtered by customer_email
        // and get total last 30 days transactions & total spent all transactions
        $last30days = Carbon::now()->subDays(30)->isoFormat('Y-MM-D HH:mm:ss');
        $aSelect = [
            'id',
            '(SELECT COUNT(id) FROM '.with(new PurchaseTransaction)->getTable().' WHERE customer_id = '.with(new Customer)->getTable().'.id AND UNIX_TIMESTAMP(transaction_at) BETWEEN UNIX_TIMESTAMP("'.$last30days.'") AND UNIX_TIMESTAMP(NOW())) AS last_30_days_purchase_total',
            '(SELECT SUM(total_spent) FROM '.with(new PurchaseTransaction)->getTable().' WHERE customer_id = '.with(new Customer)->getTable().'.id) AS purchase_spent_all_total'
        ];
        $customer = Customer::selectRaw(implode(', ', $aSelect))
            ->where('email', $customerEmail)
            ->first();
        if(!$customer) return $this->sendError('Customer data not found', [], 400);

        DB::beginTransaction();
        try {
            // remove lockdown voucher that pass the expired time
            $this->campaignVoucherService->removeLockDownNotRedeem($campaign->id);
    
            // check campaign is accessable or not
            $campaignAccessable = $this->campaignService->checkAccessable($campaign->id);
            if(!$campaignAccessable['success']) {
                // check campaign voucher active for selected customer
                $customerActiveVoucher = $this->campaignVoucherService->getActiveVoucher($campaign->id, $customer->id);
                if($customerActiveVoucher['success'] && count($customerActiveVoucher['data']) > 0) {
                    return $this->sendResponse(
                        $customerActiveVoucher['data'], 
                        $customerActiveVoucher['message']
                    );
                }
    
                return $this->sendError(
                    $campaignAccessable['message'], 
                    $campaignAccessable['data'], 
                    isset($campaignAccessable['code']) ? $campaignAccessable['code'] : 400
                );
            }
    
            if($customer->last_30_days_purchase_total < 3 
            && $customer->purchase_spent_all_total < 100) {
                return $this->sendError(
                    'Customer are not eligible to participate this campaign', 
                    [
                        'last_30_days_purchase_total' => $customer->last_30_days_purchase_total,
                        'purchase_spent_all_total' => $customer->purchase_spent_all_total,
                    ], 
                    isset($campaignAccessable['code']) ? $campaignAccessable['code'] : 400
                );
            }
            // set locked down campaign voucher to selected customer
            $campaignVoucher = $this->campaignVoucherService->setLockDownToCustomer($customer->id, $campaignAccessable['data']->id);

            DB::commit();
            return $this->sendResponse(
                $campaignVoucher['data'], 
                $campaignVoucher['message']
            );
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Something error from the server');
        }
    }

    /**
     * validate photo submission to get voucher code 
     * 
     * @param \Illuminate\Http\Request (string customer_email, string campaign_slug, string validate_file)
     * @return \Illuminate\Http\Response
     */
    public function validatePhotoSubmission(Request $request)
    {
        DB::beginTransaction();
        try {
            // check request param campaign_slug is filled
            $campaignSlug = $request->campaign_slug ?? '';
            if(!$campaignSlug) return $this->sendError('Campaign slug not found', [], 400);
            
            // check data campaign is exists filtered by campaign_slug
            $campaign = Campaign::select('id')->where('slug', $campaignSlug)->first();
            if(!$campaign) return $this->sendError('Campaign data not found', [], 400);
            
            // check request param customer_email is filled
            $customerEmail = $request->customer_email ?? '';
            if(!$customerEmail) return $this->sendError('Customer email not found', [], 400);
            
            // check data customer is exists filtered by customer_email
            $customer = Customer::select('id')->where('email', $customerEmail)->first();
            if(!$customer) return $this->sendError('Customer data not found', [], 400);

            // validate file
            $validateFile = $request->validate_file ?? true;   
            $qualifyResult = $this->campaignVoucherService->qualifyPhotoSubmission($campaign->id, $customer->id, $validateFile);
            
            DB::commit();
            if(!$qualifyResult['success']) {
                // check campaign voucher active for selected customer
                $customerActiveVoucher = $this->campaignVoucherService->getActiveVoucher($campaign->id, $customer->id);
                if($customerActiveVoucher['success'] && count($customerActiveVoucher['data']) > 0) {
                    return $this->sendResponse(
                        $customerActiveVoucher['data'], 
                        $customerActiveVoucher['message']
                    );
                }

                return $this->sendError($qualifyResult['message'], [], 400);
            }
    
            return $this->sendResponse(
                $qualifyResult['data'],
                $qualifyResult['message']
            );
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Something error from the server');
        }
    }
}
