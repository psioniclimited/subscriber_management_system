<?php

namespace App\Modules\CasHistory\Controllers;
use App\Http\Controllers\Controller;
use Datatables;
use App\Modules\SubscriberManagement\Models\FingerprintHistory;

class FingerprintHistoryController extends Controller {

	/**
	 * [allFingerprintHistory -load fingerprint history list]
	 * @return [view] [description]
	 */
	public function allFingerprintHistory(){
		return view('CasHistory::fingerprint_history');
	}

	/**
	 * [getFingerprintHistory -fingerprint history loaded with ajax in datatable]
	 * @return [json] [fingerprint history list in json format]
	 */
	public function getFingerprintHistory(){
		$fingerprint_history = FingerprintHistory::with('card')
		->where('execution_status', 1);

		return Datatables::of($fingerprint_history)
		->make(true);
	}
}