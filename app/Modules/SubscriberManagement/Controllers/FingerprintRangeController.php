<?php
namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SubscriberManagement\Models\Product;
use Illuminate\Http\Request;
use App\Jobs\FingerprintRangeJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class FingerprintRangeController extends Controller{

	/**
	 * [fingerprintRange loads fingerprint range form]
	 * @return [view] [description]
	 */
	public function fingerprintRange(){
		$products = Product::all();
        return view('SubscriberManagement::cardrange.fingerprint_range')
        ->with('products', $products);
    }

    public function fingerprintRangeProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
    	$this->dispatch(new FingerprintRangeJob($request->all(), $commandMaker, $socketHelper, $updateCommandInformationHelper));
    }
}