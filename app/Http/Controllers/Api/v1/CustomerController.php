<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Models\master\Customer;
use App\Services\CampaignService;
use App\Services\CampaignVoucherService;

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

        // check request param customer_email is filled
        $customerEmail = $request->customer_email ?? '';
        if(!$customerEmail) return $this->sendError('Customer email not found', [], 400);

        // check campaign is accessable or not
        $campaignAccessable = $this->campaignService->checkAccessable($campaignSlug);
        if(!$campaignAccessable['success']) {
            return $this->sendError(
                $campaignAccessable['message'], 
                $campaignAccessable['data'], 
                isset($campaignAccessable['code']) ? $campaignAccessable['code'] : 400
            );
        }

        // set locked down campaign voucher to selected customer
        $campaignVoucher = $this->campaignVoucherService->setLockDownToCustomer($customerEmail, $campaignAccessable['data']->id);
        if(!$campaignVoucher['success']) {
            return $this->sendError(
                $campaignVoucher['message'], 
                $campaignVoucher['data'], 
                isset($campaignVoucher['code']) ? $campaignVoucher['code'] : 400
            );
        }

        return $this->sendResponse(
            $campaignVoucher['message'], 
            $campaignVoucher['data'], 
            isset($campaignVoucher['code']) ? $campaignVoucher['code'] : 200
        );
    }
}
