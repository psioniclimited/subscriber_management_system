<?php
namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\User\Models\User;
use App\Modules\User\Helpers\RoleAccess;
use Entrust;

class ReassignCardRangeController extends Controller{
    const DEFAULT_CHECKBOX = 1;
    const REMOVE_CUSTOMER_FROM_CARD = 2;
    const TRANSFER_CUSTOMER = 3;

	public function reassignCardRange(){
        return view('SubscriberManagement::cardrange.reassign_card_range');
    }

    public function reassignCardRangeProcess(Request $request, RoleAccess $role_access){
        $from_card_number = $request->input('from_card_number');
        $to_card_number = $request->input('to_card_number');
        $users_id = null;
        $subdistributor = null;
        
        $users_id = $role_access->getDistributor($request);
        $subdistributor = $role_access->getSubdistributor($request);
        $update_cards = Card::whereBetween('card_id', [$from_card_number, $to_card_number]);
        
        if(Entrust::hasRole('admin') && $request->has('distributors_id'))
            $update_cards->update(array('users_id' => $users_id, 'subdistributor' => $subdistributor));
        elseif(Entrust::hasRole('distributor'))
            $update_cards->where('users_id', $users_id)->update(array('users_id' => $users_id, 'subdistributor' => $subdistributor));
        
        if($request->input('checkbox_operation') == self::REMOVE_CUSTOMER_FROM_CARD)
            Card::whereBetween('card_id', [$from_card_number, $to_card_number])->update(array('customers_id' => null));
        else if($request->input('checkbox_operation') == self::TRANSFER_CUSTOMER){
            $cards = Card::whereBetween('card_id', [$request->from_card_number, $request->to_card_number])->lists('customers_id');
            Customer::whereIn('customers_id', $cards)->update(['users_id' => $users_id, 'subdistributor' => $subdistributor]);
        }
           
        return back();
    }

}