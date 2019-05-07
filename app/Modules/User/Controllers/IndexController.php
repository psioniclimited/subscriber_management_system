<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\RoleUser;
use App\Modules\CableManagement\Models\Territory;
use App\Modules\CableManagement\Models\CustomerDetails;
use Lang;
use Datatables;
use Entrust;
use Illuminate\Http\Request;

/**
 * IndexController
 *
 * Controller to all the properties uith user module.
 * login, user crud, listing and more
 */
class IndexController extends Controller {

    public function index() {
        if(Auth::check()) {
            redirect("/dashboard");
        }
        else {
            return view('User::login');
        }
    }

    //Login Module
    public function loginUser(\App\Http\Requests\LoginRequest $request) {
        $userdata = array(
            'email' => $request->input('username'),
            'password' => $request->input('password'),
            'can_login' => 1
        );
        if (Auth::attempt($userdata)) {
            return redirect('dashboard');
        } 
        else {
            return redirect('login')->withErrors([$request->input('username') => $this->getFailedLoginMessage()]);
        }
    }

    protected function getFailedLoginMessage() {
        return Lang::has('auth.failed') ? Lang::get('auth.failed') : 'wrong username / password';
    }

    public function logoutUser() {
        Auth::logout();
        return redirect('login');
    }

    //User Module
    public function allUsers() {
        return view('User::all_users');
    }

    public function getUsers() {
        $users = RoleUser::join('users', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->join('territory', 'users.territory_id', '=', 'territory.id')
                ->select(['users.id as id', 'users.name', 'users.email', 'roles.display_name', 'territory.name as territory_name']);

        return Datatables::of($users)
        ->addColumn('Link', function ($users) {
            if(Entrust::can('users.update') && Entrust::can('users.delete')){
                $action_view = '<a href="' . url('/users') . '/' . $users->id . '/edit' . '"' . 'class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a class="btn btn-xs btn-danger" id="'.$users->id.'"
                data-toggle="modal" data-target="#confirm_delete">
                <i class="glyphicon glyphicon-trash"></i> Delete</a>'; 
            }

            else{
                $action_view = 'N/A';
            }
            return $action_view; 
        })
        ->make(true);
    }

    public function createUsers() {
        $getRoles = Role::all();
        // pass territory value
        $territory = Territory::all();
        return view('User::create_users')
        ->with('getRoles', $getRoles)
        ->with('territory', $territory);
    }

    public function createUsersProcess(\App\Http\Requests\UserRequest $request) {
        $addUsers = new User();

        $addUsers->name = $request->input('fullname');
        $addUsers->email = $request->input('uemail');
        $addUsers->password = bcrypt($request->input('upassword'));
        $addUsers->territory_id = $request->input('territory');


        $add_user = $addUsers->save();

        $userID = $addUsers->id;
        $roleID = $request->input('uroles');

        $user = User::find($userID);
        $role = Role::where('id', '=', $roleID)->get()->first();
        $user->attachRole($role);

        if($add_user === true) {
            $parameters = ['success'=> 'Successfully created.'];
        }
        else{
            $parameters = ['failure'=> 'Something went wrong. Try again later.'];
        }

        return back()->with($parameters);


    }

    public function editUsers($id) {
        // pass territory value
        $territory = Territory::all();
        $editUser = User::where('id', '=', $id)->get();
        $getRoles = Role::leftJoin('role_user', function($join) use ($id) {
                    $join->on('role_user.role_id', '=', 'roles.id')->where('role_user.user_id', '=', $id);
                })->get();

        return view('User::edit_users')
        ->with('getRoles', $getRoles)
        ->with('editUser', $editUser)
        ->with('territory', $territory);
    }

    public function editUsersProcess(\App\Http\Requests\UserUpdateRequest $request) {
        $userID = $request->input('uid');        
        $password = $request->input('upassword');
        
        $addUsers = User::findOrFail($userID);

        $addUsers->name = $request->input('fullname');
        $addUsers->email = $request->input('uemail');
        $addUsers->territory_id = $request->input('territory');

        if (isset($password) && $password != '') {
            $addUsers->password = bcrypt($password);
        }
        $addUsers->save();
        
        $dRoleUser = RoleUser::where('user_id', '=', $userID)->delete();

        $roleID = $request->input('uroles');

        $user = User::find($userID);
        $role = Role::where('id', '=', $roleID)->get()->first();
        $user->attachRole($role);

        return redirect('users/'.$userID.'/edit');
    }


    public function editUserOwnProfile() {
        $id = Entrust::user()->id;
        // pass territory value
        $territory = Territory::all();
        $editUser = User::where('id', '=', $id)->get();
        $getRoles="";
        if (Entrust::hasRole('admin')) {
            $getRoles = Role::leftJoin('role_user', function($join) use ($id) {
                    $join->on('role_user.role_id', '=', 'roles.id')->where('role_user.user_id', '=', $id);
                })->get();
        }
        return view('User::edit_user_own_profile')
        ->with('getRoles', $getRoles)
        ->with('editUser', $editUser)
        ->with('territory', $territory);
    }

    public function editUserOwnProfileProcess(\App\Http\Requests\UserOwnProfileUpdateRequest $request) {
        $userID = $request->input('uid');        
        $password = $request->input('upassword');
        
        $addUsers = User::findOrFail($userID);

        $addUsers->name = $request->input('fullname');
        $addUsers->email = $request->input('uemail');
        $addUsers->territory_id = $request->input('territory');

        if (isset($password) && $password != '') {
            $addUsers->password = bcrypt($password);
        }
        $addUsers->save();
        
        if (Entrust::hasRole('admin')) {
           $dRoleUser = RoleUser::where('user_id', '=', $userID)->delete();

            $roleID = $request->input('uroles');

            $user = User::find($userID);
            $role = Role::where('id', '=', $roleID)->get()->first();
            $user->attachRole($role);
        }
        
        return redirect('edit_users_own_profile')->with('success', 'Profile Successfully Updated.');
    }


    public function deleteUsers($id){
        // dd($id);
        if($id != 6){
            CustomerDetails::where('users_id', $id)->update(['users_id' => 6]);

            $deleteUsers = User::find($id);
            $deleteUsers->delete();
            return redirect('allusers');

        }
        else{
            dd('failed');
        }
        
        

    } 


}
