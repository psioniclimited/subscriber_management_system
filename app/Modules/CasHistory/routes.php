<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {
    // View card entitlement history list
    Route::get('allcardentitlementhistory', 'App\Modules\CasHistory\Controllers\CardEntitlementHistoryController@allCardEntitlementHistory')->middleware(['permission:entitle.read']);
    Route::get('getcardentitlementhistory', 'App\Modules\CasHistory\Controllers\CardEntitlementHistoryController@getCardEntitlementHistory')->middleware(['permission:entitle.read']);

    // View fingerprint history list
    Route::get('allfingerprinthistory', 'App\Modules\CasHistory\Controllers\FingerprintHistoryController@allFingerprintHistory')->middleware(['permission:fingerprint.read']);
    Route::get('getfingerprinthistory', 'App\Modules\CasHistory\Controllers\FingerprintHistoryController@getFingerprintHistory')->middleware(['permission:fingerprint.read']);

    // View blacklist history list
    Route::get('allblacklisthistory', 'App\Modules\CasHistory\Controllers\BlacklistHistoryController@allBlacklistHistory')->middleware(['permission:blacklist.read']);
    Route::get('getblacklisthistory', 'App\Modules\CasHistory\Controllers\BlacklistHistoryController@getBlacklistHistory')->middleware(['permission:blacklist.read']);


});
