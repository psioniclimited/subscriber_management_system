<?php

namespace App\Modules\CableManagement\Controllers;

use Illuminate\Http\Request;
use Form;
use Datatables;
use App\Http\Controllers\Controller;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\CableManagement\Models\CustomerDetails;
use App\Modules\CableManagement\Models\Subscription;
use App\Modules\CableManagement\Models\Territory;
use App\Modules\CableManagement\Models\Sector;
use App\Modules\CableManagement\Models\Road;
use App\Modules\CableManagement\Models\House;
use App\Modules\User\Models\RoleUser;
use Entrust;
/**
 * CollectionController
 *
 * Controller to all the properties uith Customer related data.
 * add customer, view all customers
 */

class CollectionController extends Controller {


    /**
     * [allCollectionList -all_collection_list view is loaded]
     * @return [view] [all_collection_list]
     */
    public function allCollectionList() {
        $billCollectors = RoleUser::where('role_id', '=', 2)
                ->join('users', 'role_user.user_id', '=', 'users.id')
                ->select(['users.id as id', 'users.name', 'users.email'])
                ->get();
        
        return view('CableManagement::collection.all_collection_list');
    }


    /**
     * [getCollectionList -collection list is loaded with ajax in datatable in all_collection_list view]
     * @return [json] [total collection list in json format]
     */
    public function getCollectionList() {
        $customerDetails = CustomerDetails::where('due', '=', '0')
        ->join('customers', 'customer_details.customers_id', '=', 'customers.customers_id')
        ->join('users', 'customer_details.users_id', '=', 'users.id')
        ->select(['customer_details.*', 'customers.name', 'customers.phone', 'customers.customer_code', 'users.name as bill_collector_name']);
        
        return Datatables::of($customerDetails)
        ->addColumn('Link', function ($customerDetails) {
            return '<a target="_blank" href="http://maps.google.com/maps?q='. $customerDetails->lat . ','. $customerDetails->lon .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Map</a>
                    ';
        })
        ->make(true);
    }


    /**
     * [getFilteredCollectionData - filtered collection list loaded with ajax in datatable in all_collection_list view]
     * @param  Request $request [description]
     * @return [json]           [filtered collection list in json format]
     */
    public function getFilteredCollectionData(Request $request){
        $bill_collector_id = $request ->input('bill_collector_id');
        $territory_id = $request ->input('territory_id');
        $sector_id = $request ->input('sector_id');
        // return $territory_id;
        if($bill_collector_id != null){
            $customerDetails = CustomerDetails::where('due', '=', '0')
            ->where('users_id', '=', $bill_collector_id)
            ->join('customers', 'customer_details.customers_id', '=', 'customers.customers_id')
            ->join('users', 'customer_details.users_id', '=', 'users.id')
            ->select('customer_details.*', 'customers.name', 'customers.phone', 'customers.customer_code', 'users.name as bill_collector_name');
        }
        else if($territory_id != null){
            $customerDetails = Customer::where('due', '=', '0')
            ->where('customers.territory_id', '=', $territory_id)
            ->join('customer_details', 'customers.customers_id', '=', 'customer_details.customers_id')
            ->join('users', 'customer_details.users_id', '=', 'users.id')
            ->select('customer_details.*', 'customers.name', 'customers.phone', 'customers.customer_code', 'users.name as bill_collector_name');
        }
        if($sector_id != null){
            $customerDetails = Customer::where('due', '=', '0')
            ->where('sectors_id', '=', $sector_id)
            ->join('customer_details', 'customers.customers_id', '=', 'customer_details.customers_id')
            ->join('users', 'customer_details.users_id', '=', 'users.id')
            ->select('customer_details.*', 'customers.name', 'customers.phone', 'customers.customer_code', 'users.name as bill_collector_name');
        }


       
        return Datatables::of($customerDetails)
        ->addColumn('Link', function ($customerDetails) {
            return '<a target="_blank" href="http://maps.google.com/maps?q='. $customerDetails->lat . ','. $customerDetails->lon .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Map</a>';
        })
        ->make(true);

    }




    /**
     * [allDueList -all_collection_list view is loaded]
     * @return [view] [all_collection_list]
     */
    public function allDueList() {
        $billCollectors = RoleUser::where('role_id', '=', 2)
                ->join('users', 'role_user.user_id', '=', 'users.id')
                ->select(['users.id as id', 'users.name', 'users.email'])
                ->get();
        
        return view('CableManagement::collection.all_due_list');
    }


    /**
     * [getDueList -collection list is loaded with ajax in datatable in all_collection_list view]
     * @return [json] [total collection list in json format]
     */
    public function getDueList() {
        $customerDetails = CustomerDetails::where('due', '=', '1')
        ->join('customers', 'customer_details.customers_id', '=', 'customers.customers_id')
        ->join('users', 'customer_details.users_id', '=', 'users.id')
        ->select(['customer_details.*', 'customers.name', 'customers.phone', 'customers.customer_code', 'users.name as bill_collector_name']);
        
        return Datatables::of($customerDetails)
        ->addColumn('Link', function ($customerDetails) {
            return '<a target="_blank" href="http://maps.google.com/maps?q='. $customerDetails->lat . ','. $customerDetails->lon .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Map</a>
                    ';
        })
        ->make(true);
    }
    

    /**
     * [getFilteredDueData - filtered collection list loaded with ajax in datatable in all_collection_list view]
     * @param  Request $request [description]
     * @return [json]           [filtered collection list in json format]
     */
    public function getFilteredDueData(Request $request){
        $bill_collector_id = $request ->input('bill_collector_id');
        $territory_id = $request ->input('territory_id');
        $sector_id = $request ->input('sector_id');
        // return $territory_id;
        if($bill_collector_id != null){
            $customerDetails = CustomerDetails::where('due', '=', '1')
            ->where('users_id', '=', $bill_collector_id)
            ->join('customers', 'customer_details.customers_id', '=', 'customers.customers_id')
            ->join('users', 'customer_details.users_id', '=', 'users.id')
            ->select('customer_details.*', 'customers.name', 'customers.phone', 'customers.customer_code', 'users.name as bill_collector_name');
        }
        else if($territory_id != null){
            $customerDetails = Customer::where('due', '=', '1')
            ->where('customers.territory_id', '=', $territory_id)
            ->join('customer_details', 'customers.customers_id', '=', 'customer_details.customers_id')
            ->join('users', 'customer_details.users_id', '=', 'users.id')
            ->select('customer_details.*', 'customers.name', 'customers.phone', 'customers.customer_code', 'users.name as bill_collector_name');
        }
        if($sector_id != null){
            $customerDetails = Customer::where('due', '=', '1')
            ->where('sectors_id', '=', $sector_id)
            ->join('customer_details', 'customers.customers_id', '=', 'customer_details.customers_id')
            ->join('users', 'customer_details.users_id', '=', 'users.id')
            ->select('customer_details.*', 'customers.name', 'customers.phone', 'customers.customer_code', 'users.name as bill_collector_name');
        }


       
        return Datatables::of($customerDetails)
        ->addColumn('Link', function ($customerDetails) {
            return '<a target="_blank" href="http://maps.google.com/maps?q='. $customerDetails->lat . ','. $customerDetails->lon .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Map</a>';
        })
        ->make(true);

    }


   







}
