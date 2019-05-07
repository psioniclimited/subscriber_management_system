<?php
namespace App\Modules\SubscriberManagement\Repository;
use App\Modules\User\Models\User;
use App\Modules\User\Models\RoleUser;
use App\Modules\SubscriberManagement\Models\Card;

use App\Modules\CableManagement\Models\Customer;
use App\Modules\SubscriberManagement\Models\SetTopBox;

use Entrust;
use Auth;

class CardRepository{
	
	public function getAllCards(){
		$cards = Card::with('user');
		$user = Auth::user();
		if($user->hasRole('admin')){
			return $cards;
		}
		else if($user->hasRole('distributor')){
			$cards->where('users_id', $user->id);
		}
	}

  public function getAllCardsForAdminForMsgEmail($attribute, $value, $columns = ['*']){
      return Card::where($attribute, "LIKE", "%{$value}%")
                  ->get($columns);    
  }

  public function allCardsForDistributorForMsgEmail($attribute, $value, $columns = ['*'], $distributor_id){
      return Card::where($attribute, "LIKE", "%{$value}%")
                  ->where('users_id', $distributor_id)
                    ->limit(20)
                  ->get($columns);
  }

  public function allCardsForSubdistributorForMsgEmail($attribute, $value, $columns = ['*'], $sub_distributor_id) {
    return Card::where($attribute, "LIKE", "%{$value}%")
                ->where('subdistributor', $sub_distributor_id)
                ->get($columns);
  }

  public function getLimitedCards($request){
      $cards = Card::where('card_id', "LIKE", "%{$request->input('term')}%")->limit(10);

      if (Entrust::hasRole('admin'))
          return $cards->get(['cards.id', 'cards.card_id as text']);
      elseif(Entrust::hasRole('distributor'))
          return $cards->where('users_id', Entrust::user()->id)->get(['cards.id', 'cards.card_id as text']);
      elseif(Entrust::hasRole('sub_distributor'))
          return $cards->where('subdistributor', Entrust::user()->id)->get(['cards.id', 'cards.card_id as text']);
  }





	public function allCardsForDistributorByAttribute($attribute, $value, $columns = ['*'], $distributor_id){
        // $sub_distributors = User::where('managed_by', $distributor_id)->lists('id');
        // $sub_distributors->push($distributor_id);
        return Card::where($attribute, "LIKE", "%{$value}%")
        	->whereDoesntHave('customers')
            // ->whereIn('users_id', $sub_distributors)
            ->where('users_id', $distributor_id)
            ->get($columns);
    }

  	public function allCardsForSubdistributorByAttribute($attribute, $value, $columns = ['*'], $sub_distributor_id) {
  		return Card::where($attribute, "LIKE", "%{$value}%")
  			->whereDoesntHave('customers')
            ->where('subdistributor', $sub_distributor_id)
            ->get($columns);
  	}
}