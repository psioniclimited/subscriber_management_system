<?php

namespace App\Modules\CableManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Modules\CableManagement\Models\Territory;
use App\Modules\CableManagement\Models\Sector;
use App\Modules\CableManagement\Models\Road;
use App\Modules\CableManagement\Models\House;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\CableManagement\Models\CustomerDetails;
use DB;
use JWTAuth;
/**
 * syncs data with mobile
 */
class MobileDataController extends Controller
{
	/**
	 * Send location to device
	 * @return json sector/block,road, house
	 */
    public function getLocations(){
   //  	$collection = [];
   //  	$territory = Territory::all();
   //  	foreach ($territory as $terr) {
	  //   	$collection = collect([
	  //   		['territory'=> $terr],
	  //   		['sectors'=>$terr->sector]
			// ]);
			// foreach ($terr->sector as $sector) {
			// 	$collection->push($sector->road);

			// 	foreach ($sector->road as $road) {
			// 		$collection->push($road->house);
			// 	}
			// }
   //  	}
   //  	return response()->json($collection);

        //Better more efficient version of code
        $locations = Territory::with('sector.road.house')->get();
        // $locations = $locations['territory', $locations];
        // dd($locations);
        return response()->json($locations);
    }

    /**
     * Sync customer with device
     * @param  Request $request last_id, limit
     * @param int 				last_id last synchronized id
     * @param int 				limit limit=~100
     * @return json 			customers
     */
    public function getCustomers(Request $request){
        $user = JWTAuth::parseToken()->authenticate();

        $last_id = $request->input('last_id');
        $limit = $request->input('limit');

        // $last_id = 20;
        // $limit = 100;

        // $customers = Customer::where('id', '>', $last_id)->take($limit);
        // $query_customers = "
        //     SELECT 
        //     customers.customers_id, 
        //     customers.customer_code, 
        //     customers.name,
        //     customers.address,
        //     customers.phone,
        //     customers.houses_id,
        //     subscription_types.price,
        //     'JULY' as last_paid,
        //     customers.updated_at
        //     FROM customers
        //     JOIN subscription_types ON
        //     customers.subscription_types_id = subscription_types.id
        //     WHERE customers.customers_id > ? LIMIT ?
        // ";
        $query_customers = Customer::join('subscription_types', 'customers.subscription_types_id', '=', 'subscription_types.id')
        ->where('customers_id', '>', $last_id)
        ->take($limit)
        ->get(['customers_id', 'customers.customer_code', 'customers.name', 'address', 'phone', 'houses_id', 'price', 'customers.last_paid', 'updated_at']);


        // $customers = DB::select($query_customers, [$last_id, $limit]);
        return response()->json($query_customers);
    }

    public function postCustomerdata(Request $request){
        $customers_id = $request->input('customers_id');
        $editCustomer = Customer::findOrFail($customers_id);
        $editCustomer->name = $request->input('name');
        $editCustomer->phone = $request->input('phone');

        $editCustomer->save();
        // dd($editCustomer);
        return response('success');

    }

    public function postTest(){
        dd('hello');
    }
    /**
     * Save customer information
     * @param  Request $request
     * 
     */
    public function postCustomers(Request $request){
        $customer = new Customer;
    }

    public function getTesteloquent(){
        $houses = House::with('road')->get();
        return response()->json($houses);
    }

    /**
     * [postBillingdata add billing data to customer_details table]
     * @param  Request $request [default request]
     * @return [response]           [return success or failure]
     */
    public function postBillingdata(Request $request){
        
        $addCustomerDetails = new CustomerDetails();

        $addCustomerDetails->customers_id = $request->input('customers_id');
        $addCustomerDetails->total = $request->input('total');
        $addCustomerDetails->timestamp = $request->input('timestamp');
        // dd($request->input('timestamp'));

        // Get last_paid data from Customer table
        $customer = Customer::findOrFail($request->input('customers_id'));
        // Format data accordingly and save it in last_paid_carbon
        $last_paid_carbon = \Carbon\Carbon::createFromFormat('Y-m-d', $customer->last_paid);
        // Get the number of months of bill received
        $last_paid_date_num = $request->input('last_paid_date_num');
        // Add that number of months to the last_paid_carbon date 
        $last_paid_date = $last_paid_carbon->addMonth($last_paid_date_num);
        // save in last_paid_date
        $addCustomerDetails->last_paid_date = $last_paid_date;

        $addCustomerDetails->lat = $request->input('lat');
        $addCustomerDetails->lon = $request->input('lon');
        // Get user id from token
        $user = JWTAuth::parseToken()->authenticate();
        $userID = $user->id;
        $addCustomerDetails->users_id = $userID;

        $saved = $addCustomerDetails->save();

        $customer->last_paid = $last_paid_date;

        $customer->save();

        if($saved){
            return response("success");   
        }
        else{
            return  response("failed");
        }

    }
}
