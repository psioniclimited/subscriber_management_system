<?php

namespace App\Modules\CasHistory\Controllers;
use App\Http\Controllers\Controller;
use Datatables;
use App\Modules\SubscriberManagement\Models\CardEntitlementHistory;

class CardEntitlementHistoryController extends Controller {

	/**
	 * [allCardEntitlementHistory -load card entitlement history list]
	 * @return [view] [description]
	 */
	public function allCardEntitlementHistory(){
		return view('CasHistory::card_entitlement_history');
	}

	/**
	 * [getCardEntitlementHistory -card entitlement history loaded with ajax in datatable]
	 * @return [json] [card entitlement history list in json format]
	 */
	public function getCardEntitlementHistory(){
		$entitlement_history = CardEntitlementHistory::with('card')
		->where('execution_status', 1);

		return Datatables::of($entitlement_history)
		->make(true);
	}
}