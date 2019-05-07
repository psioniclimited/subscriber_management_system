<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use Entrust;
use App\Modules\User\Models\RoleUser;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\CableManagement\Models\Territory;
use App\Modules\CableManagement\Models\CustomerDetails;
use App\Modules\SubscriberManagement\Models\Card;


class DistributorController extends Controller {

    private $role;
    private $user;

    function __construct(){
        $this->role = Role::where('name', '=', 'distributor')->get()->first();
        $this->admin = User::where('name', '=', 'admin')->get()->first();
    }

    /**
     * [allDistributors -loads all distributors list]
     * @return [view] [description]
     */
    public function allDistributors() {
        return view('SubscriberManagement::distributor.all_distributors');
    }

    /**
     * [getDistributors -all distributors are loaded with ajax in a datatable]
     * @return [json] [all distributors list in json format]
     */
    public function getDistributors() {
        $distributors = User::with('territory')
        ->whereHas('roles', function ($query) {
            $query->where('id', $this->role->id);
        });
                
        return Datatables::of($distributors)
        ->addColumn('Link', function ($distributors) {
            return '<a href="' . url('/distributor') . '/' . $distributors->id . '/edit' . '"' . 'class="btn btn-xs btn-primary">
                    <i class="glyphicon glyphicon-edit"></i> Edit</a>
                    <a class="btn btn-xs btn-danger" id="'.$distributors->id.'"
                    data-toggle="modal" data-target="#confirm_delete">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                    </a>';
        })
        ->addColumn('Sub_Distributor_Link', function ($distributors) {
            return '<a href="' . url('/subdistributorsbydistributor') . '/' . $distributors->id . '"' . 'class="btn   btn-xs btn-info">
                    <i class="glyphicon glyphicon-edit"></i> View</a>';
        })
        ->make(true);
    }

    /**
     * [createDistributor -loads create new distributor form]
     * @return [view] [description]
     */
    public function createDistributor() {
        $getRoles = Role::all();
        // pass territory value
        $territory = Territory::all();
        return view('SubscriberManagement::distributor.create_distributor')
        ->with('getRoles', $getRoles)
        ->with('territory', $territory);
    }

    /**
     * [createDistributorProcess -adds new distributor to the database]
     * @param  \App\Http\Requests\UserRequest $request [description]
     * @return [type]                                  [description]
     */
    public function createDistributorProcess(\App\Http\Requests\UserRequest $request) {
        
        $addDistributor = new User();
        $addDistributor->name = $request->input('fullname');
        $addDistributor->email = $request->input('uemail');
        $addDistributor->password = bcrypt($request->input('upassword'));
        $addDistributor->territory_id = $request->input('territory');

        $addDistributor->save();
        
        $addDistributor->attachRole($this->role);

        User::where('id', $addDistributor->id)->update(['managed_by' => $addDistributor->id]);

        return redirect('alldistributors')->with('success', 'Distributor "' . $request->input('fullname') . '" Successfully Created & Assigned.');
    }


    /**
     * [editDistributor -loads edit distributor form]
     * @param  [int] $id [distributor id]
     * @return [view]     [description]
     */
    public function editDistributor($id){
        $distributor = User::findOrFail($id);
        $territory = Territory::all();
        return view('SubscriberManagement::distributor.edit_distributor')
        ->with('distributor', $distributor)
        ->with('territory', $territory);
    }

    /**
     * [editDistributorProcess -changes made to the form is saved in the database]
     * @param  Request $request [description]
     * @param  [int]  $id      [distributor id]
     * @return [redirect]           [distributor edit form]
     */
    public function editDistributorProcess(Request $request, $id){

        $editDistributor = User::findOrFail($id);

        $editDistributor->name = $request->input('fullname');
        $editDistributor->email = $request->input('uemail');
        $editDistributor->territory_id = $request->input('territory');
        $password = $request->input('upassword');
        if (isset($password) && $password != '') {
            $editDistributor->password = bcrypt($password);
        }

        $editDistributor->save();

        return redirect('distributor/'.$id.'/edit');
    }

    /**
     * [revokeDistributor -loads revoke distributor form]
     * @return [view] [loads form]
     */
    public function revokeDistributor(){
        return view('SubscriberManagement::distributor.revoke_distributor');    
    }

    /**
     * [revokeDistributorProcess -revokes permissions/cards from a distributor]
     * @param  Request $request [description]
     * @return [json]           [description]
     */
    public function revokeDistributorProcess(Request $request){
        $distributor_id = $request->input('users_id');
        $revoke_cards = Card::where('users_id', $distributor_id)->update(['users_id' => $this->admin->id]);
        return back();
        
    }


   

}
