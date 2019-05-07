<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
// use App\Modules\User\Models\RoleUser;

use Datatables;
class HistoryController extends Controller {


    public function cardHistory() {
        return view('SubscriberManagement::card_history');
    }

    public function messageHistory(){
    	return view('SubscriberManagement::message_history');
    }
 

}
