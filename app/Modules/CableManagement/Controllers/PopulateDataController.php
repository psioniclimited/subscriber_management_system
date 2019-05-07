<?php

namespace App\Modules\CableManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\CableManagement\Models\Road;
use App\Modules\CableManagement\Models\House;
use App\Modules\CableManagement\Models\Subscription;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\User\Models\User;

class PopulateDataController extends Controller {


    
    public function populateFromExcel(){
        // Excel::load('/home/shakib/Documents/Billing app materials/sector_1.xls', function($reader) {
        //     $results = $reader->get();
        //     foreach ($results as $data) {
        //         $addCustomer = new Customer();

        //         $addCustomer->customer_code = $data->id;
        //         $addCustomer->name = $data->name . " - " . $data->label;
        //         $addCustomer->territory_id = 1;
        //         $addCustomer->sectors_id = 1;

        //         $checkSubscription = Subscription::where('name','=',$data->type)->first();
        //         // dd($checkSubscription);
        //         if( $checkSubscription == NULL ){
        //             $addSubcription = new Subscription();
        //             $addSubcription->name = $data->type;
        //             $addSubcription->save();

        //             $addCustomer->subscription_types_id = $addSubcription->id;
        //         }
        //         else{
        //             $addCustomer->subscription_types_id = $checkSubscription->id;
        //         }
                
                
        //         $checkRoad = Road::where('road','=',$data->road)->first();
        //         if( is_null($checkRoad) ){
        //             $addRoad = new Road();
        //             $addRoad->road = $data->road;
        //             $addRoad->sectors_id = $addCustomer->sectors_id;
        //             $addRoad->save();

        //             $addCustomer->roads_id = $addRoad->id;
        //         }
        //         else{
        //             $addCustomer->roads_id = $checkRoad->id;
        //         }
        //         //where road == the new road
        //         $checkHouse = House::where('house','=',$data->hs)
        //         ->where('roads_id', '=', $addCustomer->roads_id)
        //         ->first();
        //         if( is_null($checkHouse) ){
        //             $addHouse = new House();
        //             $addHouse->house = $data->hs;
        //             $addHouse->roads_id = $addCustomer->roads_id;
        //             $addHouse->save();

        //             $addCustomer->houses_id = $addHouse->id;
        //         }
        //         else{
        //             $addCustomer->houses_id = $checkHouse->id; 
        //         }
        //         $addCustomer->number_of_connections = $data->tv;
        //         $addCustomer->connection_start_date = $data->conn_date;
        //         $addCustomer->monthly_bill = $data->bill;
             
        //         $addCustomer->save();
        //         // var_dump($data);
        //      } 

        // });

        // dd('success');
    }

    public function populateCardData(){ 
        //  Excel::load('/home/shakib/Documents/subscriber_management_materials/imam/subscriber.xls', function($reader) {
        //     $results = $reader->get();

        //     foreach ($results as $data) {
        //         $distributor_id;
        //         $does_user_exist = User::where('name', $data->name)->first();
        //         // dd($does_user_exist == null);
        //         if($does_user_exist == null){
        //             $addDistributor = new User();
        //             $addDistributor->name = $data->name; 
        //             $addDistributor->password = bcrypt('123456'); 
        //             $addDistributor->territory_id = 1;
        //             $fullname = explode(" ", $data->name);
        //             $first_name = strtolower(reset($fullname));
        //             $last_name = strtolower(end($fullname));
        //             $addDistributor->email = $first_name . $last_name . '@digi21.com';

        //             $addDistributor->save();
        //             $addDistributor->attachRole(4);
        //             $distributor_id = $addDistributor->id;
        //         }
        //         else{
        //             $distributor_id = $does_user_exist->id;
        //         }

        //         $addCard = new Card();
        //         $addCard->card_id = $data->card_id;
        //         $addCard->users_id = $distributor_id;

        //         $addCard->save();
                

        //     } 

        //     dd('success');

        // });
    }
}
