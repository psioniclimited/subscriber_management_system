<?php

namespace App\Modules\CasHistory\Controllers;
use App\Http\Controllers\Controller;
use Datatables;
use App\Modules\SubscriberManagement\Models\BlacklistHistory;

class BlacklistHistoryController extends Controller {

	/**
	 * [allBlacklistHistory -load blacklist history list]
	 * @return [view] [description]
	 */
	public function allBlacklistHistory(){
		return view('CasHistory::blacklist_history');
	}

	/**
	 * [getBlacklistHistory -blacklist history loaded with ajax in datatable]
	 * @return [json] [blacklist history list in json format]
	 */
	public function getBlacklistHistory(){
		$blacklist_history = BlacklistHistory::with('card')
		->where('execution_status', 1);

		return Datatables::of($blacklist_history)
		->make(true);
	}
}