<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('customer')->group(function() {
    Route::get('check-eligible-campaign-voucher', 'Api\v1\CustomerController@checkEligibleCampaignVoucher');
    Route::post('validate-photo-submission', 'Api\v1\CustomerController@validatePhotoSubmission');
});