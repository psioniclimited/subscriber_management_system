<?php
/**
 * Created by PhpStorm.
 * User: saad
 * Date: 2/6/17
 * Time: 1:20 PM
 */

namespace App\Modules\SubscriberManagement\Helpers;


class ParseResponseFromCas
{
    public function parseResponseFromCas($response){
//        Sample response
//        00180201000500000000
        $response_from_cas = substr($response, 12);
        if($response_from_cas == '00000000'){
            return "success";
        }
        else{
            return $response_from_cas;
        }
    }
}