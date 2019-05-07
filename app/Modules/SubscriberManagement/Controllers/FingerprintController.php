<?php
namespace App\Modules\SubscriberManagement\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Modules\SubscriberManagement\Models\FingerprintHistory;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\SubscriberManagement\Models\Card;

class FingerprintController extends Controller {

    /**
     * [fingerprintCustomerProcess - save relevant data in fingerprinthistory from customer list & forward fingerprint command]
     * @param  Request                        $request                        [description]
     * @param  CommandMaker                   $commandMaker                   [description]
     * @param  SocketHelper                   $socketHelper                   [description]
     * @param  UpdateCommandInformationHelper $updateCommandInformationHelper [description]
     * @return [type]                                                         [description]
     */
    public function fingerprintCustomerProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
        $customer_id = $request->input('customer_id'); 
        // Works for one card per customer only
        // For multiple cards functionality replace first() by all() in the code below
        $card = Card::where('customers_id', '=', $customer_id)->lists('id')->first();
        // Form data
        $form_data = $request->input('form_data');
        // Fingerprint duration in seconds
        $fingerprint_duration = $form_data[1]['value'];
        // Fingerprint expire date & time
        $fingerprint_expire_date = $form_data[2]['value'];
        $fingerprint_expire_time = $form_data[3]['value'];
        $merged_expire_date_time = $fingerprint_expire_date.' '.$fingerprint_expire_time;

        $fingerprintHistory = new FingerprintHistory();
        $fingerprintHistory->duration = $fingerprint_duration;
        $fingerprintHistory->expired_time = $merged_expire_date_time;
        $fingerprintHistory->cards_id = $card;

        $fingerprintHistory->save();

        $command_string = $commandMaker->getFingerPrintCommand($fingerprintHistory);

        $message = hex2bin($command_string);
        $response_from_cas = $socketHelper->sendCommandToCas($message);

        $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $fingerprintHistory);
        
        return "success";
    }

    /**
     * [fingerprintCardProcess - save relevant data in fingerprinthistory from card list & forward fingerprint command]
     * @param  Request                        $request                        [description]
     * @param  CommandMaker                   $commandMaker                   [description]
     * @param  SocketHelper                   $socketHelper                   [description]
     * @param  UpdateCommandInformationHelper $updateCommandInformationHelper [description]
     * @return [type]                                                         [description]
     */
    public function fingerprintCardProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
        $card_id = $request->input('card_id'); 
        // Form data
        $form_data = $request->input('form_data');
        // Fingerprint duration in seconds
        $fingerprint_duration = $form_data[1]['value'];
        // Fingerprint expire date & time
        $fingerprint_expire_date = $form_data[2]['value'];
        $fingerprint_expire_time = $form_data[3]['value'];
        $merged_expire_date_time = $fingerprint_expire_date.' '.$fingerprint_expire_time;

        $fingerprintHistory = new FingerprintHistory();
        $fingerprintHistory->duration = $fingerprint_duration;
        $fingerprintHistory->expired_time = $merged_expire_date_time;
        $fingerprintHistory->cards_id = $card_id;
        
        $fingerprintHistory->save();

        $command_string = $commandMaker->getFingerPrintCommand($fingerprintHistory);

        $message = hex2bin($command_string);
        $response_from_cas = $socketHelper->sendCommandToCas($message);

        $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $fingerprintHistory);
        
        return "success";
    }

    
}
