<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
// use App\Modules\User\Models\RoleUser;

use Datatables;
class ProviderController extends Controller {


    public function allProvidersList() {
        return view('SubscriberManagement::all_providers');
    }

  

}
