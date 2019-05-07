<?php
namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Models\Product;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Models\CardEntitlementHistory;
use App\Modules\SubscriberManagement\Helpers\CasCommandHelper;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Jobs\EntitleRangeJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class EntitleCardRangeController extends Controller{
    use DispatchesJobs;

	/**
	 * [entitleCardRange loads entitle card range form]
	 * @return [view] [description]
	 */
	public function entitleCardRange(){
		$products = Product::all();
        return view('SubscriberManagement::cardrange.entitle_card_range')
        ->with('products', $products);
    }

    public function entitleCardRangeProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
//         $between = [intval($request->input('from_card_number')), intval($request->input('to_card_number'))];
//         $cards = Card::whereBetween('card_id', $between)->get();
// //        dd($cards);
//         foreach ($cards as $card){
//             $entitle = new CardEntitlementHistory();
//             $entitle->products_id = $request->input('product');
//             $entitle->start_time = $request->input('datepicker_start_time');
//             $entitle->end_time = $request->input('datepicker_end_time');
//             $entitle->operations_id = 1;
//             $entitle->cards_id = $card->id;
//             $entitle->save();
//             $command_string = $commandMaker->getEntitleCommand($entitle);
// //            dd($command_string);
//             $message = hex2bin($command_string);
//             $response_from_cas = $socketHelper->sendCommandToCas($message);
//             echo $response_from_cas;
//         }
//         return response()->json($cards);

// 		dd('In EntitleCardRangeController.');
        // $entitle_range_job = new EntitleRangeJob($request, $commandMaker, $socketHelper);
        // dd($entitle_range_job);
        $this->dispatch(new EntitleRangeJob($request->all(), $commandMaker, $socketHelper, $updateCommandInformationHelper));
        
    }
}