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
class UnentitleCardRangeController extends Controller{

	/**
	 * [unentitleCardRange loads unentitle card range form]
	 * @return [view] [description]
	 */
	public function unentitleCardRange(){
		$products = Product::all();
        return view('SubscriberManagement::cardrange.unentitle_card_range')
        ->with('products', $products);
    }

    public function unentitleCardRangeProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper){
		dd('In UnentitleCardRangeController.');
    }
}