<?php

namespace App\Modules\CableManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\RoleUser;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\CableManagement\Models\Territory;
use App\Modules\CableManagement\Models\CustomerDetails;
use Datatables;
class BillCollectorController extends Controller {

    private $role;

    function __construct(){
        $this->role = Role::where('name', '=', 'bill_collector')->get()->first();
    }

    public function allBillCollectors() {
        return view('CableManagement::billcollector.all_bill_collectors');
    }

    public function getBillCollectors() {
        $users = RoleUser::where('role_id', '=', $this->role->id)
        		->join('users', 'role_user.user_id', '=', 'users.id')
                ->join('territory', 'users.territory_id', '=', 'territory.id')
                ->select(['users.id as id', 'users.name', 'users.email','territory.name as territory_name']);
                
        return Datatables::of($users)
        ->addColumn('Link', function ($users) {
            return '<a href="' . url('/users') . '/' . $users->id . '/edit' . '"' . 'class="btn btn-xs btn-primary">
                    <i class="glyphicon glyphicon-edit"></i> Edit</a>
                    <a class="btn btn-xs btn-danger" id="'.$users->id.'"
                    data-toggle="modal" data-target="#confirm_delete">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                    </a>';
        })
        ->make(true);


    }

    public function createBillCollector() {
        $getRoles = Role::all();
        // pass territory value
        $territory = Territory::all();
        return view('CableManagement::billcollector.create_bill_collector')
        ->with('getRoles', $getRoles)
        ->with('territory', $territory);
    }

    public function createBillCollectorProcess(\App\Http\Requests\UserRequest $request) {
        $addUsers = new User();

        $addUsers->name = $request->input('fullname');
        $addUsers->email = $request->input('uemail');
        $addUsers->password = bcrypt($request->input('upassword'));
        $addUsers->territory_id = $request->input('territory');


        $addUsers->save();

        $addUsers->attachRole($this->role);

        return redirect('allbillcollectors');
    }

    public function deleteBillCollectors($id){
        CustomerDetails::where('users_id', $id)->update(['users_id' => 1]);
        $deleteBillCollectors = User::find($id);
        $deleteBillCollectors->delete();
        return redirect('allbillcollectors');

    } 

   


   

}
