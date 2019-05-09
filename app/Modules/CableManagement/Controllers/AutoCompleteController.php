<?php

namespace App\Modules\CableManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SubscriberManagement\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Modules\SubscriberManagement\Repository\CardRepository;

use App\Modules\CableManagement\Models\Territory;
use App\Modules\CableManagement\Models\Sector;
use App\Modules\CableManagement\Models\Road;
use App\Modules\CableManagement\Models\House;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\User\Models\RoleUser;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\User\Models\User;
use DB;
use Entrust;

class AutoCompleteController extends Controller
{
    
    public function getTerritory(Request $request){
    	$search_term = $request->input('term');
    	$territory = Territory::where('sector', "LIKE", "%{$search_term}%")->get(['id', 'sector as text']);
    	return response()->json($territory);
    }
    /**
     * return sectors matching search term and territorry
     * @param  Request $request
     * @return json
     */
    public function getSector(Request $request){
    	$search_term = $request->input('term');
    	$territory = $request->input('value_term');
    	$sectors = Sector::where('sector', "LIKE", "%{$search_term}%")
    	->where('territory_id', '=', $territory)
    	->get(['id', 'sector as text']);

    	return response()->json($sectors);
    }

    /**
     * return roads matching search term and sector
     * @param  Request $request
     * @return json
     */
    public function getRoad(Request $request){
    	$search_term = $request->input('term');
    	$territory = $request->input('value_term');

    	$sectors = Road::where('road', "LIKE", "%{$search_term}%")
    	->where('sectors_id', '=', $territory)
    	->get(['id', 'road as text']);

    	return response()->json($sectors);
    }

    /**
     * return houses matching search term and road
     * @param  Request $request
     * @return json
     */
    public function getHouse(Request $request){
    	$search_term = $request->input('term');
    	$road = $request->input('value_term');

    	$sectors = House::where('house', "LIKE", "%{$search_term}%")
    	->where('roads_id', '=', $road)
    	->get(['id', 'house as text']);

    	return response()->json($sectors);	
    }

    public function getAllterritory(Request $request){
        $territory = Territory::get(['id', 'name as text']);
        return response()->json($territory);
    }

    public function getCustomerdetails(Request $request){
        $customer_id = $request->input('customer_id');
        $customer = Customer::with('house.road.sector.territory', 'user.manager', 'cards', 'set_top_boxes', 'subdistributor_user')
                            ->get()
                            ->find($customer_id);
        return response()->json($customer);
    }


    /**
     * [getAllSectors will return all sectors from the database]
     * @param  Request $request [description]
     * @return [json]           [description]
     */
    public function getAllsectors(Request $request){
        $sector = Sector::get(['id', 'sector as text']);
        return response()->json($sector);
    }

    public function getAllbillcollectors(Request $request){
        $bill_collector = RoleUser::where('role_id', '=', 2)
                        ->join('users', 'role_user.user_id', '=', 'users.id')
                        ->get(['users.id as id', 'users.name as text']);

        return response()->json($bill_collector);
    }

    public function getAllcardids(Request $request, CardRepository $customer_repo)  {
        if ($request->has('sub_distributor_id'))    // Logged in as Admin or Distributor. Selected option as sub-distributor.
            return $customer_repo->allCardsForSubdistributorByAttribute('card_id', $request->input('term'), ['cards.id', 'cards.card_id as text'],  $request->input('sub_distributor_id'));
        elseif($request->has('distributor_id'))     // Logged in as Admin. Selected option as distributor only.
            return $customer_repo->allCardsForDistributorByAttribute('card_id', $request->input('term'), ['cards.id', 'cards.card_id as text'],  $request->input('distributor_id'));
        elseif(Entrust::hasRole('distributor'))     // Logged in as Distributor. Never selected any sub-distributor.
            return $customer_repo->allCardsForDistributorByAttribute('card_id', $request->input('term'), ['cards.id', 'cards.card_id as text'], Entrust::user()->id);
        elseif(Entrust::hasRole('sub_distributor'))  // Logged in as Sub Distributor.
            return $customer_repo->allCardsForSubdistributorByAttribute('card_id', $request->input('term'), ['cards.id', 'cards.card_id as text'],  Entrust::user()->id);
    }

    public function getAllcards(Request $request, CardRepository $customer_repo){
        return $customer_repo->getLimitedCards($request);
    }

    public function getTerritorywhichdoesnthavesector(Request $request){
        $territory = Territory::whereDoesntHave('sector')
        ->get(['id', 'name as text']);
        return response()->json($territory);
    }

    public function getSectorwhichdoesnthaveroad(Request $request){
        $sector = Sector::whereDoesntHave('road')
        ->get(['id', 'sector as text']);
        return response()->json($sector);
    }

    public function getRoadwhichdoesnthavehouse(Request $request){
        $road = Road::whereDoesntHave('house')
        ->get(['id', 'road as text']);
        return response()->json($road);
    }

    public function getHousewhichdoesnthavecustomer(Request $request){
        $road = House::whereDoesntHave('customer')
        ->get(['id', 'house as text']);
        return response()->json($road);
    }

    public function getProduct(Request $request){
        $search_term = $request->input('term');
//        dd($search_term);

        $sectors = Product::where('name', "LIKE", "%{$search_term}%")
            ->get(['product_id as id', 'name as text']);

        return response()->json($sectors);

    }



    
}
