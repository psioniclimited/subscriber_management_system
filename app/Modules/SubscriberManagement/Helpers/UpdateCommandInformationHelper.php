<?php

namespace App\Modules\SubscriberManagement\Helpers;
use App\Modules\SubscriberManagement\Models\ErrorCodes;

class UpdateCommandInformationHelper
{
    public function updateCommandInformation($response_from_cas, $object){
       if($response_from_cas == "success"){
        //success
        $object->execution_status = 1;
        $object->update();

        $parameters = ['status'=> 'Successful'];
      }
      else{
        //get value from config display error
        //1- $response_from_cas = D0000002
        //2- Display the message from database
        //3- if error CardEntitlementHistory save error_code
        $error_message = ErrorCodes::where('error_code', $response_from_cas)->get();
        $error_message_formatted = $error_message[0]->description; 
        $object->error_codes_id = $error_message[0]->id;
        $object->update();
        
        $parameters = ['status'=> $error_message_formatted];
      }
      return $parameters;
    }
}