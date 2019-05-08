<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Repository\CardEntitlementRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Datatables;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\SubscriberManagement\Models\Product;
use App\Modules\SubscriberManagement\Models\CardEntitlementHistory;
use App\Modules\SubscriberManagement\Helpers\CasCommandHelper;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\CableManagement\Repository\CustomerRepository;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;
use App\Modules\CableManagement\Models\CustomerNotes;
use Entrust;
use App\Modules\SubscriberManagement\Repository\ProductRepository;

class EntitleController extends Controller {

   	/**
     * [entitleCard - loads manage entile card form]
     * @param  Request            $request         [description]
     * @param  [type]             $id              [description]
     * @param  CustomerRepository $customerdetails [description]
     * @param  ProductRepository  $products        [description]
     * @return [view]                              [description]
     */
    public function entitleCard(Request $request, $id, CustomerRepository $customerdetails, ProductRepository $products){
   		return view('SubscriberManagement::entitle.manage_entitle')
   		->with('products', $products->getProducts())
   		->with('customer_id', $id)
      ->with(['customer_details' => $customerdetails->getCustomerDetails('customers_id', $id, ['customers_id', 'name', 'phone', 'address','users_id', 'houses_id'])])
      ->with(['card_details' => $customerdetails->getCardDetails('customers_id', $id, ['id', 'card_id', 'blacklisted'])]);
    }

    /**
     * [entitleProcess - save entitle related data in db and forward entitle command]
     * @param  Request                        $request                        [description]
     * @param  CommandMaker                   $commandMaker                   [description]
     * @param  SocketHelper                   $socketHelper                   [description]
     * @param  UpdateCommandInformationHelper $updateCommandInformationHelper [description]
     * @return [back]                                                         [description]
     */
    public function entitleProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper, CardEntitlementRepository $cardEntitlementRepository){

      $customer = Customer::findorFail($request->input('customer_id'));
      $cards = $customer->cards()->get();
      
      $product = Product::find($request->input('product'));

        if($product->days != 0) {
          foreach ($cards as $card) {
            $latest_card_entitlement_history = $cardEntitlementRepository->getLatestCardEntitlementHistory($card);
            if(! is_null($latest_card_entitlement_history) ){
                $previous_end_time_formatted = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $latest_card_entitlement_history->end_time)->format('d/m/Y g:i A');
                $previous_end_time_carbon = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $latest_card_entitlement_history->end_time);
                $new_end_time_formatted = $previous_end_time_carbon->addDays($product->days)->format('d/m/Y g:i A');
            }
            else{
                $previous_end_time_formatted = \Carbon\Carbon::now()->format('d/m/Y g:i A');
                $previous_end_time_carbon = \Carbon\Carbon::now();
                $new_end_time_formatted = $previous_end_time_carbon->addDays($product->days)->format('d/m/Y g:i A');
            }


            $entitle = new CardEntitlementHistory();
            $entitle->cards_id = $card->id;
            $entitle->operations_id = 1;
            $entitle->products_id = $request->input('product');
            $entitle->start_time = $previous_end_time_formatted;
            $entitle->end_time = $new_end_time_formatted;
            $entitle->product_amount = 1;
            $entitle->send_or_not_send = 1;
            $entitle->tapping = 0;
            $entitle->customers_id = $customer->customers_id;

            $entitle->save();
          }
        }
        // product->days == 0
        else {
          $start_date = $request->input('datepicker_start_date');
          $start_time = $request->input('timepicker_start_time');
          $merged_start_date_time = $start_date.' '.$start_time;

          $end_date = $request->input('datepicker_end_date');
          $end_time = $request->input('timepicker_end_time');
          $merged_end_date_time = $end_date.' '.$end_time;

          foreach ($cards as $card) {
            $entitle = new CardEntitlementHistory();
            $entitle->cards_id = $card->id;
            $entitle->operations_id = 1;
            $entitle->products_id = $request->input('product');
            $entitle->start_time = $merged_start_date_time;
            $entitle->end_time = $merged_end_date_time;
            $entitle->product_amount = 1;
            $entitle->send_or_not_send = 1;
            $entitle->tapping = 0;
            $entitle->customers_id = $customer->customers_id;

            $entitle->save();
          }
        }


      $command_string = $commandMaker->getEntitleCommand($entitle);
      $message = hex2bin($command_string);
      $response_from_cas = $socketHelper->sendCommandToCas($message);

      $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $entitle);

      return back()->with($response_from_error);
    }

    /**
     * [getEntitlementHistory - entitlement history is loaded with ajax in a datatable]
     * @param  Request $request [description]
     * @return [json]           [entitlement history list in json format]
     */
    public function getEntitlementHistory(Request $request) {
      $customer_id = $request->input('customer_id');
      // Get card which belongs to that customer
      $card = Card::where('customers_id', '=', $customer_id)->lists('id')->all();
      // Get entitlement history of cards which belong to that customer
      $entitlementHistory = CardEntitlementHistory::with('card','operation','product')
      ->where('operations_id', 1)
      ->where('unentitled', 0)
      ->where('end_time', '>', Carbon::now()->toDateTimeString())
      ->where('execution_status', 1)
      ->whereIn('cards_id', $card);

      return Datatables::of($entitlementHistory)
      ->addColumn('Link', function($entitlementHistory) {
          $unentitle_button = '<a href="' . url('/customers') . '/' . $entitlementHistory->id . '/unentitle' . '"' . 'class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-edit"></i> Unentitle</a>';
          return $unentitle_button;
      })
      ->make(true);
    }

    /**
     * [unEntitleCard - forward unentitle command]
     * @param  Request                        $request                        [description]
     * @param  CommandMaker                   $commandMaker                   [description]
     * @param  SocketHelper                   $socketHelper                   [description]
     * @param  [type]                         $id                             [description]
     * @param  UpdateCommandInformationHelper $updateCommandInformationHelper [description]
     * @return [type]                                                         [description]
     */
    public function unEntitleCard(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, $id, UpdateCommandInformationHelper $updateCommandInformationHelper){
      $unentitle = CardEntitlementHistory::with('card', 'product')->find($id);
      $unentitle_end_time = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $unentitle->end_time)->timestamp;
      $old_start_time = $unentitle->start_time;
      $unentitle->execution_status = 0;
      $unentitle->start_time = (new Carbon($unentitle->end_time))->format('d/m/Y g:i A');

      $command_string = $commandMaker->getEntitleCommand($unentitle);
      $message = hex2bin($command_string);
      $response_from_cas = $socketHelper->sendCommandToCas($message);

      $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $unentitle);

      if($response_from_error["status"] == "Successful"){
        $unentitle->start_time = (new Carbon($old_start_time))->format('d/m/Y g:i A');
        $unentitle->unentitled = 1;
        $unentitle->save();
      }
      else{
        //error
        return back()->with($response_from_error["status"]);
      }

      return back();
    }

    /**
     * [customerNoteProcess add note to db]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function customerNoteProcess(Request $request){
      // Get user id from Entrust
      $user_id = Entrust::user()->id;

      $customer_note_data = $request->all();
      $customer_note_data['users_id'] = $user_id;
      $customer_note = CustomerNotes::create($customer_note_data);

      return back();
    }

    /**
     * [getCustomerNotes -list of customer notes is loaded with ajax in datatable in manage entitle view]
     * @param  Request $request [description]
     * @return [type]           [list of notes in json format]
     */
    public function getCustomerNotes(Request $request){
      $customer_id = $request->input('customer_id');
      // Get customer notes
      $customer_notes = CustomerNotes::with('user')
      ->where('customers_id','=', $customer_id);

      return Datatables::of($customer_notes)
      ->make(true);

    }

}
