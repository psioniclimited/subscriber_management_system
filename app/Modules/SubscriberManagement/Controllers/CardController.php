<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\SubscriberManagement\Models\BlacklistHistory;
use App\Modules\SubscriberManagement\Repository\CardRepository;
use App\Modules\CableManagement\DataTables\CardDataTable;
use App\Modules\User\Models\User;
use App\Modules\User\Helpers\RoleAccess;
use Entrust;
use Datatables;
use DB;
use Carbon\Carbon;

class CardController extends Controller {
    const DEFAULT_CHECKBOX = 1;
    const REMOVE_CUSTOMER_FROM_CARD = 2;
    const TRANSFER_CUSTOMER = 3;

    /**
     * [allCardsList -loads all cards list ]
     * @return [view] [description]
     */
    public function allCards(Request $request, CardDataTable $dataTable) {
        $dataTable->setDistributor($request->distributor_id);
        $dataTable->setSubDistributor($request->sub_distributor_id);
        return $dataTable->render('SubscriberManagement::card.all_cards');
    }

    /**
     * [addCard -loads add new card form]
     */
    public function addCard(){
    	return view('SubscriberManagement::card.add_card');

    }

    /**
     * [addCardProcess adds new card to database]
     * @param Request $request [description]
     */
    public function addCardProcess(\App\Http\Requests\CardRequest $request) {
        $card = new Card();
        $card->card_id = $request->card_id;
        $card->users_id = $request->input('distributors_id');
        if ($request->has('sub_distributors_id'))
            $card->subdistributor = $request->input('sub_distributors_id');
        
        $card->save();

        return redirect('allcards')->with('success', 'Card "' . $card->card_id . '" Successfully Created & Assigned.');
    }

    /**
     * [editCard -edit card form is displayed]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editCard($id) {
        $card = Card::with('customers')->findOrFail($id);
        return view('SubscriberManagement::card.edit_card')
        ->with('card', $card)
        ->with('customers', $card->customers);
    }

    /**
     * [editCardProcess -changes made to edit card form is saved in db]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function editCardProcess(Request $request, $id, RoleAccess $role_access) {
        $edit_card = Card::findOrFail($id);
        $edit_card->card_id = $request->card_id;
        
        if(Entrust::hasRole('admin') && $request->has('distributors_id'))
            $edit_card->users_id = $request->input('distributors_id');
        elseif(Entrust::hasRole('distributor'))
            $edit_card->users_id = Entrust::user()->id;
        
        $edit_card->subdistributor = $role_access->getSubdistributor($request);
        
        if($request->input('checkbox_operation') == self::REMOVE_CUSTOMER_FROM_CARD)
            $edit_card->customers_id = null;
        elseif($request->input('checkbox_operation') == self::TRANSFER_CUSTOMER)
            Customer::where('customers_id', $edit_card->customers_id)->update(['users_id' => $edit_card->users_id, 'subdistributor' => $edit_card->subdistributor]);

        $edit_card->save();
        
        return redirect('cards/'.$id.'/edit');
    }

}