<?php 
namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\SubscriberManagement\Models\Card;

class CreateCardRangeController extends Controller{

	public function createCardRange(){
		return view('SubscriberManagement::cardrange.create_card_range');
	}

	public function createCardRangeProcess(Request $request){
		$from_card_number = $request->input('from_card_number');
		$to_card_number = $request->input('to_card_number');
        $users_id = null;
        $subdistributor = null;
        
        if($request->has('distributors_id'))
            $users_id = $request->input('distributors_id');
		if ($request->has('sub_distributors_id'))
            $subdistributor = $request->input('sub_distributors_id');
        
        $existing_card = collect([]);
		
		for ($card_id = $from_card_number; $card_id <= $to_card_number; $card_id++) { 
			if(Card::where('card_id', $card_id)->get()->isEmpty()){
				$createCard = new Card();
				$createCard->card_id = $card_id;
				$createCard->users_id = $users_id;
				$createCard->subdistributor = $subdistributor;
				$createCard->save();	
			}
			else
				$existing_card->push((int)$card_id);
		}
		if($existing_card->isEmpty())
			return back()->with('success', 'Cards Successfully Created & Assigned.');
		else
			return back()->with('success', 'Cards Successfully Created & Assigned. ' . $existing_card . ' cards could not be created as they already exist.');
	}
}