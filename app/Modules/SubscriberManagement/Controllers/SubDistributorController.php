<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\RoleUser;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\CableManagement\Models\Territory;
use App\Modules\CableManagement\Models\CustomerDetails;
use Datatables;
use Illuminate\Http\Request;
use App\Modules\SubscriberManagement\Models\Card;
use Entrust;
use App\Modules\SubscriberManagement\Repository\SubDistributorRepository;

class SubDistributorController extends Controller {

    private $role_sub_distributor;
    private $user;

    function __construct(){
        $this->role_sub_distributor = Role::where('name', '=', 'sub_distributor')->get()->first();
        $this->user = User::where('name', '=', 'admin')->get()->first();
    }

    /**
     * [allSubDistributors -loads all sub distributors list]
     * @return [view] [description]
     */
    public function allSubDistributors() {
        return view('SubscriberManagement::distributor.all_sub_distributors');
    }

    /**
     * [getSubDistributors -all sub distributors are loaded with ajax in a datatable]
     * @return [json] [all sub distributors in json format]
     */
    public function getSubDistributors() {
        $user = Entrust::user();
        if($user->hasRole('admin')){
            $sub_distributors = User::with('territory', 'managed_by')
                                    ->whereHas('roles', function ($query) {
                                        $query->where('id', $this->role_sub_distributor->id);
                                    });
        }
        else if($user->hasRole('distributor')){
            $sub_distributors = User::with('territory', 'managed_by')
                                    ->whereHas('roles', function ($query) use ($user) {
                                        $query->where('id', $this->role_sub_distributor->id);
                                    })
                                    ->where('managed_by', $user->id);
        }

        return Datatables::of($sub_distributors)
        ->addColumn('Link', function ($sub_distributors) {
            return '<a href="' . url('/sub_distributor') . '/' . $sub_distributors->id . '/edit' . '"' . 'class="btn btn-xs btn-primary">
                    <i class="glyphicon glyphicon-edit"></i> Edit</a>
                    <a class="btn btn-xs btn-danger" id="'.$sub_distributors->id.'"
                    data-toggle="modal" data-target="#confirm_delete">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                    </a>';
        })
        ->make(true);
    }

    /**
     * [createSubDistributor -loads create new sub distributor form]
     * @return [view] [description]
     */
    public function createSubDistributor() {
        $getRoles = Role::all();
        // pass territory value
        $territory = Territory::all();
        return view('SubscriberManagement::distributor.create_sub_distributor')
        ->with('getRoles', $getRoles)
        ->with('territory', $territory);
    }

    /**
     * [createSubDistributorProcess -adds new sub distributor to the database]
     * @param  \App\Http\Requests\UserRequest $request [description]
     * @return [type]                                  [description]
     */
    public function createSubDistributorProcess(\App\Http\Requests\UserRequest $request) {
        $addSubDistributor = new User();

        $addSubDistributor->name = $request->input('fullname');
        $addSubDistributor->email = $request->input('uemail');
        $addSubDistributor->password = bcrypt($request->input('upassword'));
        $addSubDistributor->territory_id = $request->input('territory');
        // Get role name from Entrust
        $distributor_role = Entrust::hasRole('distributor');
        // Get user id from Entrust
        $distributor_id = Entrust::user()->id;
        if($distributor_role){
            $addSubDistributor->managed_by = $distributor_id;
        }
        else{
            $addSubDistributor->managed_by = $request->input('users_id');
        }

        $addSubDistributor->save();
        
        $addSubDistributor->attachRole($this->role_sub_distributor);

        return redirect('allsubdistributors')->with('success', 'Sub distributor "' . $request->input('fullname') . '" Successfully Created & Assigned.');
    }

    /**
     * [editSubDistributor -loads edit sub distributor form]
     * @param  [int] $id [sub distributor id]
     * @return [view]     [description]
     */
    public function editSubDistributor($id){
        $sub_distributor = User::findOrFail($id);
        $territory = Territory::all();
        return view('SubscriberManagement::distributor.edit_sub_distributor')
        ->with('sub_distributor', $sub_distributor)
        ->with('territory', $territory);
    }

    /**
     * [editDistributorProcess -changes made to the form is saved in the database]
     * @param  Request $request [description]
     * @param  [int]  $id      [distributor id]
     * @return [redirect]           [distributor edit form]
     */
    public function editSubDistributorProcess(Request $request, $id){

        $editDistributor = User::findOrFail($id);

        $editDistributor->name = $request->input('fullname');
        $editDistributor->email = $request->input('uemail');
        $editDistributor->territory_id = $request->input('territory');
        $password = $request->input('upassword');
        if (isset($password) && $password != '') {
            $editDistributor->password = bcrypt($password);
        }

        $editDistributor->save();

        return redirect('sub_distributor/'.$id.'/edit');
    }

    public function subDistributorByDistributor($id){
        $distributor = User::findorFail($id);

        return view('SubscriberManagement::distributor.sub_distributors_by_distributor')
        ->with('distributor_id', $id)
        ->with('distributor_name', $distributor->name);
    }

    public function getSubDistributorByDistributor(Request $request, SubDistributorRepository $subdistributors){
        $distributor_id = $request->input('distributor_id');
        return $subdistributors->getSubDistributors('managed_by', $distributor_id);
    }

   


  
   

}
