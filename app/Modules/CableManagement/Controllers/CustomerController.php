<?php

namespace App\Modules\CableManagement\Controllers;

use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\CableManagement\Models\CustomerDetails;
use App\Modules\CableManagement\Models\Subscription;
use App\Modules\CableManagement\Models\Territory;
use App\Modules\CableManagement\Models\Sector;
use App\Modules\CableManagement\Models\Road;
use App\Modules\CableManagement\Models\House;
use App\Modules\User\Models\RoleUser;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Models\SetTopBox;
use App\Modules\SubscriberManagement\Models\CardEntitlementHistory;
use App\Modules\User\Models\User;

use Illuminate\Support\Facades\Auth;
use App\Modules\CableManagement\Repository\CustomerRepository;

use App\Modules\CableManagement\DataTables\CustomerDataTable;
use Carbon\Carbon;
use App\Modules\User\Helpers\RoleAccess;
use App\Modules\CableManagement\Datatables\CustomersDatatable;
use Form;
// use Datatables;
use Crypt;
use Entrust;
use DB;
/**
 * CustomerController
 *
 * Controller to all the properties uith Customer related data.
 * add customer, view all customers
 */

class CustomerController extends Controller {

    /**
     * [allCustomers -all_customers view is loaded]
     * @return [view] [all_customers]
     */
    public function allCustomers(Request $request,CustomerDataTable $dataTable) {
        $dataTable->setCustomerWithCardOption($request->customer_with_card_option);
        return $dataTable->render('CableManagement::customer.all_customers');
    }

    /**
     * [createCustomer -create customer form is loaded with the given view]
     * @return [view] [shows the customer create form]
     */
    public function createCustomer() {
        // pass territory value
        $territory = Territory::all();
        $subscription_types = Subscription::all();

        return view('CableManagement::customer.create_customer')
        ->with('territory', $territory)
        ->with('subscription_types', $subscription_types);
    }

    /**
     * [createCustomerProcessForAdmin -new customer is added to the database]
     * @param  \App\Http\Requests\CustomerRequest $request [description]
     * @return [redirect]                                      [saves data in database]
     */
    
    public function createCustomerProcess(\App\Http\Requests\CustomerRequest $request, RoleAccess $role_access) {
        $addCustomer = new Customer($request->all());
        if(Entrust::hasRole('admin') || Entrust::hasRole('distributor')){
            $addCustomer->users_id = $role_access->getDistributor($request);
            $addCustomer->subdistributor = $role_access->getSubdistributor($request);
        }
        elseif (Entrust::hasRole('sub_distributor')) {
            $addCustomer->users_id = User::with('manager')->find(Entrust::user()->id)->manager->id; 
            $addCustomer->subdistributor = Entrust::user()->id;
        }
        
        $addCustomer->save();

        /* Update customers_id in cards table */
        Card::where('id', $request->input('card_id'))->update(['customers_id' => $addCustomer->customers_id]);
        
        /* Update customers_id in stbs table */
        SetTopBox::where('id', $request->input('set_top_box_sn'))->update(['customers_id' => $addCustomer->customers_id]);
        
        return redirect('/allcustomers')->with('success', 'Customer "' . $request->name . '" Successfully Created & Assigned.');
    }

    /**
     * [createTerritoryProcess -new territory is added to the database]
     * @param  \App\Http\Requests\TerritoryRequest $request [territory request]
     * @return [success]                                       [success is returned when query is executed]
     */
    public function createTerritoryProcess(\App\Http\Requests\TerritoryRequest $request) {
        $addTerritory = new Territory();

        $addTerritory->name = $request->input('territory_modal');

        $addTerritory->save();

        return response()->json(["status"=>"success", "id"=> $addTerritory->id, "text" => $request->input('territory_modal')]);
    }

    public function deleteTerritoryProcess(Request $request) {
        // Form data
        $form_data = $request->input('form_data');
        // Territory id
        $territory_id = $form_data[1]['value'];
        $deleteTerritory = Territory::whereDoesntHave('sector')->where('id', $territory_id);
        $deleted = $deleteTerritory->delete();
        if($deleted === 1){
            return response()->json(["status"=>"success", "id"=> $territory_id]); 
        }
        else{
            return response()->json(["status"=>"failed"]);
        }
    }

    /**
     * [createSectorProcess -new sector is added to the database]
     * @param  \App\Http\Requests\SectorRequest $request [description]
     * @return [success]                                    [success is returned when query is executed]
     */
    public function createSectorProcess(\App\Http\Requests\SectorRequest $request) {
        $addSector = new Sector();

        $addSector->sector = $request->input('sector_modal');
        $addSector->territory_id = $request->input('sector_modal_territory');

        $addSector->save();
        return response()->json(["status"=>"success", "id"=> $addSector->id, "text" => $request->input('sector_modal')]);
    }

    public function deleteSectorProcess(Request $request) {
        // Form data
        $form_data = $request->input('form_data');
        // Territory id
        $sector_id = $form_data[1]['value'];
        $deleteSector = Sector::whereDoesntHave('road')->where('id', $sector_id);
        $deleted = $deleteSector->delete();
        if($deleted === 1){
            return response()->json(["status"=>"success", "id"=> $sector_id]); 
        }
        else{
            return response()->json(["status"=>"failed"]);
        }
    }

    /**
     * [createRoadProcess -new road is added to the database]
     * @param  \App\Http\Requests\RoadRequest $request [description]
     * @return [success]                                  [success is returned when query is executed]
     */
    public function createRoadProcess(\App\Http\Requests\RoadRequest $request) {
        $addRoad = new Road();

        $addRoad->road = $request->input('road_modal');
        $addRoad->sectors_id = $request->input('road_modal_sector');

        $addRoad->save();

        return response()->json(["status"=>"success", "id"=> $addRoad->id, "text" => $request->input('road_modal')]);

    }

    public function deleteRoadProcess(Request $request) {
        // Form data
        $form_data = $request->input('form_data');
        // Territory id
        $house_id = $form_data[1]['value'];
        $deleteSector = Road::whereDoesntHave('house')->where('id', $house_id);
        $deleted = $deleteSector->delete();
        if($deleted === 1){
            return response()->json(["status"=>"success", "id"=> $house_id]); 
        }
        else{
            return response()->json(["status"=>"failed"]);
        }
    }

    /**
     * [createHouseProcess -new house is added to the database]
     * @param  \App\Http\Requests\HouseRequest $request [description]
     * @return [success]                                   [success is returned when query is executed]
     */
    public function createHouseProcess(\App\Http\Requests\HouseRequest $request) {
        $addHouse = new House();

        $addHouse->house = $request->input('house_modal');
        $addHouse->roads_id = $request->input('house_modal_road');

        $addHouse->save();

        return response()->json(["status"=>"success", "id"=> $addHouse->id, "text" => $request->input('house_modal')]);

    }

    public function deleteHouseProcess(Request $request) {
        // Form data
        $form_data = $request->input('form_data');
        // Territory id
        $house_id = $form_data[1]['value'];
        $deleteSector = House::whereDoesntHave('customer')->where('id', $house_id);
        $deleted = $deleteSector->delete();
        if($deleted === 1){
            return response()->json(["status"=>"success", "id"=> $house_id]); 
        }
        else{
            return response()->json(["status"=>"failed"]);
        }
    }


    /**
     * [editCustomers -customer edit form is displayed]
     * @param  [int] $id [customer id]
     * @return [view]     [edit customer form]
     */
    public function editCustomers($id) {
       
        $customer = Customer::findOrFail($id);
        // pass required values
        $territory = Territory::all();
        $subscription_types = Subscription::all(); 

        return view('CableManagement::customer.edit_customers')
        ->with('customer', $customer)
        ->with('territory', $territory)
        ->with('subscription_types', $subscription_types);
    }

    /**
     * [editCustomersProcess -changes made to the edit customer form is saved to the database]
     * @param  \App\Http\Requests\CustomerUpdateRequest $request [customer update request]
     * @return [redirect]                                            [customer edit form]
     */
    public function editCustomersProcess(\App\Http\Requests\CustomerUpdateRequest $request, $id, RoleAccess $role_access) {
        $editCustomer = Customer::with('cards')->findOrFail($id);
        $editCustomer->update($request->all());
        
        if(Entrust::hasRole('admin') || Entrust::hasRole('distributor')){
            $editCustomer->users_id = $role_access->getDistributor($request);
            $editCustomer->subdistributor = $role_access->getSubdistributor($request);
        }
        elseif (Entrust::hasRole('sub_distributor')) {
            $editCustomer->users_id = User::with('manager')->find(Entrust::user()->id)->manager->id; 
            $editCustomer->subdistributor = Entrust::user()->id;
        }

        $editCustomer->save();
        
        /* Updating customers_id of the Old Card to null */
        isset($editCustomer->cards[0]) ? $old_card_id = $editCustomer->cards[0]->id : $old_card_id = null;
        Card::where('id', $old_card_id)->update(['customers_id' => null]);
        
        /* Updating customers_id of the Card with New Value */
        $request->has('card_id') ? $card_id = $request->input('card_id') : $card_id = null;
        Card::where('id', $card_id)->update(['customers_id' => $editCustomer->customers_id]);
        
        /*Updating customers_id of the Old Set Top Box to null */
        SetTopBox::where('customers_id', $editCustomer->customers_id)->update(['customers_id' => null]);
        /* Updating customers_id of the new Set Top Box id  */
        SetTopBox::where('id', $request->input('set_top_box_sn'))->update(['customers_id' => $editCustomer->customers_id]);
        
        return back();
    }
    
    

    /**
     * [deleteCustomer - delete customer function]
     * @param  [int]             $id             [customer id]
     * @param  CustomerRepository $deleteCustomer [description]
     * @return [type]                             [description]
     */
    public function deleteCustomer($id, CustomerRepository $deletecustomer){
        return $deletecustomer->deleteCustomer($id);
    } 


    /** TO BE REMOVED */
    public function chart_data_view(){
        return view('CableManagement::chart.view_chart');
    }

    public function chart_data(Request $request){
       return response()->json([
         'labels' => ["January", "February", "March", "April", "May", "June", "July"],
         'data' => [65, 59, 80, 81, 56, 55, 40]
        ]);
    }

    /** TO BE REMOVED */


    /**
     * [activateCustomerProcess -updated active field of customer to 1]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function activateCustomerProcess(Request $request){
        $customer_id = $request->input('customer_id');
        $customer_is_activated = Customer::where('customers_id', $customer_id)
        ->update(['active' => 1]);

        return "success";
    }

    /**
     * [deactivateCustomerProcess -update active field of customer to 0]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function deactivateCustomerProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
        $customer_id = $request->input('customer_id');
        $form_data = $request->input('form_data');
        $radio_button_value = $form_data[1]['value'];

        $today = Carbon::today()->toDateTimeString();

        // Get card which belongs to that customer
        $card = Card::where('customers_id', '=', $customer_id)->lists('id')->all();

        // Get entitlement history of cards which belong to that customer
        $card_entitlement_history = CardEntitlementHistory::whereIn('cards_id', $card)
        ->where('unentitled','=','0')
        ->where('end_time', '>', $today)
        ->get();
        
        // unentitle code here 
        if (!$card_entitlement_history->isEmpty()) { 
          foreach ($card_entitlement_history as $unentitle) {
            $unentitle_end_time = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $unentitle->end_time)->timestamp;
            $old_start_time = $unentitle->start_time;
            $unentitle->execution_status = 0;
            $unentitle->start_time = (new Carbon($unentitle->end_time))->format('d/m/Y g:i A');

            $command_string = $commandMaker->getEntitleCommand($unentitle);
            $message = hex2bin($command_string);
            $response_from_cas = $socketHelper->sendCommandToCas($message);

            $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $unentitle);

            $unentitle->start_time = (new Carbon($old_start_time))->format('d/m/Y g:i A');
            $unentitle->unentitled = 1;
            $unentitle->save();
          }
        }

        if($radio_button_value == "permanently_deactivate"){
          // Update customers_id in cards table to null
          Card::where('customers_id', $customer_id)
          ->update(['customers_id' => null]);
        }
        
        $deactivate_customer = Customer::where('customers_id', $customer_id)
        ->update(['active' => 0]);

        return "success";
    }


    public function viewCustomerList(Request $request, CustomersDatatable $dataTable){

        return $dataTable->render('CableManagement::customer.view_customers');
    }

}
