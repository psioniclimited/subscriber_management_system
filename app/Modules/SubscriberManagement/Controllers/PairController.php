<?php
namespace App\Modules\SubscriberManagement\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\SubscriberManagement\Models\PairHistory;
use App\Modules\SubscriberManagement\Models\Card;

class PairController extends Controller {

    public function pairCustomerProcess(Request $request){
        
        // Customer id
        $customer_id = $request->input('customer_id'); 
        $customer = Customer::with('cards', 'set_top_boxes')
                            ->find($customer_id);
 
        // Form data
        $form_data = $request->input('form_data');
        // Pair duration in days
        $pair_duration = $form_data[1]['value'];
        // Today's date
        $today = Carbon::today();
        // Add days to today
        $formatted_duration = $today->addDays($pair_duration);

        $pairHistory = new PairHistory();

        $pairHistory->expired_time = $formatted_duration;
        $pairHistory->cards_id = $customer->cards[0]->id; 
        $pairHistory->stbs_id = $customer->set_top_boxes[0]->id; 

        $pairHistory->save();

        // Update paired status to 1 in cards table, (0 = not paired, 1 = paired)
        $card_blacklisted_status = Card::where('id', $customer->cards[0]->id)
                                       ->update(['paired' => 1]);
        
        return "success";

    }

    
}
