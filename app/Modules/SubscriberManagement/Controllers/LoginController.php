<?php
namespace App\Modules\SubscriberManagement\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\User\Models\User;

class LoginController extends Controller {

    /**
     * [enableLoginProcess -update can_login status to 1]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function enableLoginProcess(Request $request){
        $user_id = $request->input('sub_distributor_id');
        $user_can_login = User::where('id', $user_id)
        ->update(['can_login' => 1]);

        return "success";
    }

    /**
     * [disableLoginProcess -update can_login status to 0]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function disableLoginProcess(Request $request){
        $user_id = $request->input('sub_distributor_id');
        $user_can_login = User::where('id', $user_id)
        ->update(['can_login' => 0]);

        return "success";
    }

}
