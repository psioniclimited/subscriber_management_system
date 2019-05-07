<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\RoleUser;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Repository\DistributorRepository;
use App\Modules\SubscriberManagement\Repository\SetTopBoxRepository;
use Entrust;
class SelectDataController extends Controller{

    /**
     * [getDistributors -get all distributors]
     * @param  Request               $request      [description]
     * @param  DistributorRepository $distributors [description]
     * @return [type]                              [description]
     */
    public function getDistributors(Request $request, DistributorRepository $distributors){
        return $distributors->allDistributorsByAttribute('name', $request->input('term'), ['users.id', 'users.name as text']);
    }
    
    public function getSubdistributors(Request $request, DistributorRepository $distributors){
        $request->has('distributor_id') ? $distributor_id = $request->input('distributor_id') : $distributor_id = Entrust::user()->id;
        return $distributors->allSubDistributorsByAttribute('name', $request->input('term'), ['users.id', 'users.name as text'], $distributor_id);
    }

    /**
     * [getSettopboxmodels -get all set top box models]
     * @param  Request             $request   [description]
     * @param  SetTopBoxRepository $settopbox [description]
     * @return [type]                         [description]
     */
    public function getSettopboxmodels(Request $request, SetTopBoxRepository $settopbox){
    	return $settopbox->allSetTopBoxModels('name', $request->input('term'), ['id', 'name as text']);
    }

    public function getSettopboxes(Request $request, SetTopBoxRepository $set_top_box){
        if ($request->has('sub_distributor_id'))    // Logged in as Admin or Distributor. Selected option as sub-distributor.
            return $set_top_box->allSetTopBoxesForSubdistributorByAttribute('number', $request->input('term'), ['id', 'number as text'],  $request->input('sub_distributor_id'));
        elseif($request->has('distributor_id'))     // Logged in as Admin. Selected option as distributor only.
            return $set_top_box->allSetTopBoxesForDistributorByAttribute('number', $request->input('term'), ['id', 'number as text'],  $request->input('distributor_id'));
        elseif(Entrust::hasRole('distributor'))    // Logged in as Distributor. Never selected any sub-distributor. Or Logged in as Sub Distributor.
            return $set_top_box->allSetTopBoxesForDistributorByAttribute('number', $request->input('term'), ['id', 'number as text'], Entrust::user()->id);
        elseif(Entrust::hasRole('sub_distributor'))  // Logged in as Sub Distributor.
            return $set_top_box->allSetTopBoxesForSubdistributorByAttribute('number', $request->input('term'), ['id', 'number as text'], Entrust::user()->id);
    }

    public function getSettopboxbrands(Request $request, SetTopBoxRepository $settopbox){
        return $settopbox->allSetTopBoxBrands('name', $request->input('term'), ['id', 'name as text']); 
    }

    public function getSettopboxmodelsbybrand(Request $request, SetTopBoxRepository $settopbox){
        return $settopbox->allSetTopBoxModelsByBrand('name', $request->input('term'), $request->input('value_term'), ['id', 'name as text']);
    }

    /**
     * [getDistributorbycard -get distributor by card]
     * @param  Request               $request             [description]
     * @param  DistributorRepository $distributor_by_card [description]
     * @return [type]                                     [description]
     */
    public function getDistributorbycard(Request $request, DistributorRepository $distributor_by_card){
        return $distributor_by_card->distributorAndSubDistributorByCard($request->input('cards_id'));
    }

    /**
     * [getSettopboxdetails -get set top box details]
     * @param  Request             $request          [description]
     * @param  SetTopBoxRepository $settopboxdetails [description]
     * @return [type]                                [description]
     */
    public function getSettopboxdetails(Request $request, SetTopBoxRepository $settopboxdetails){
        return $settopboxdetails->allSetTopBoxDetails($request->input('set_top_box_id'));
    }
}
