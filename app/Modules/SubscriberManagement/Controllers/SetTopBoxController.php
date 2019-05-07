<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use Entrust;
use App\Modules\SubscriberManagement\Models\SetTopBox;
use App\Modules\SubscriberManagement\Models\SetTopBoxBrand;
use App\Modules\SubscriberManagement\Models\SetTopBoxModel;
use App\Modules\User\Helpers\RoleAccess;
use App\Modules\User\Models\User;

class SetTopBoxController extends Controller {

    /**
     * [addSetTopBox -loads add new set top box form]
     */
    public function addSetTopBox(){
        // pass set top box brand value
        $set_top_box_brand = SetTopBoxBrand::all();
        return view('SubscriberManagement::settopbox.add_set_top_box')
                ->with('set_top_box_brand', $set_top_box_brand);
    }
    
    /**
     * [addSetTopBoxProcess -add set top box related data to db]
     * @param Request $request [description]
     */
    public function addSetTopBoxProcess(Request $request, RoleAccess $role_access){
        $set_top_box = new SetTopBox($request->all());
        $set_top_box->users_id = $role_access->getDistributor($request);
        $set_top_box->subdistributor = $role_access->getSubdistributor($request);
        $set_top_box->save();
        
        return redirect('/allsettopboxes')->with('success', 'Set Top Box "' . $request->number . '" Successfully Created & Assigned.');
    }
    
    /**
     * [addSetTopBoxBrandProcess -add set top box brand to db]
     * @param Request $request [description]
     */
    public function addSetTopBoxBrandProcess(\App\Http\Requests\SetTopBoxBrandRequest $request){
        $add_brand = SetTopBoxBrand::create($request->all());
        return response()->json(["status"=>"success", "id"=> $add_brand->id, "text" => $add_brand->name]);
    }


    /**
     * [addSetTopBoxModelProcess -add set top box model related data to db]
     * @param Request $request [description]
     */
    public function addSetTopBoxModelProcess(\App\Http\Requests\SetTopBoxModelRequest $request){

        $set_top_box_model_data = $request->all();
        $set_top_box_model_data['stb_brands_id'] = $request->input('model_modal_brand');
        $add_set_top_box_model = SetTopBoxModel::create($set_top_box_model_data);
        
        return response()->json(["status"=>"success", "id"=> $add_set_top_box_model->id, "text" => $add_set_top_box_model->name]);
    }

    /**
     * [allSetTopBox -loads all set top box list in datatable]
     * @return [view] [description]
     */
    public function allSetTopBox() {
        return view('SubscriberManagement::settopbox.all_set_top_box');
    }

    /**
     * [getSetTopBox -loads data from db onto datatable]
     * @return [json] [set top box data]
     */
    public function getSetTopBox(){
        $user_id = Entrust::user();
        if(Entrust::hasRole('admin')){
            // Get all Set Top Boxes
            // $all_set_top_box = SetTopBox::with('set_top_box_model.set_top_box_brand', 'user.manager');
            $all_set_top_box = SetTopBox::with('set_top_box_model.set_top_box_brand', 'user','subdistributor_user');
        }
        else if(Entrust::hasRole('distributor')) {
            // Get only cards specific to a distributor
            // $sub_distributors = User::where('managed_by', $user_id->id)->lists('id');
            // $sub_distributors->push($user_id->id);
            // $all_set_top_box = SetTopBox::with('set_top_box_model.set_top_box_brand', 'user.manager')->whereIn('users_id', $sub_distributors);
            $all_set_top_box = SetTopBox::with('set_top_box_model.set_top_box_brand', 'user','subdistributor_user')->where('users_id', Entrust::user()->id); 
         }
        else if(Entrust::hasRole('sub_distributor')) {
            // Get only cards specific to a sub-distributor
            // $all_set_top_box = SetTopBox::with('set_top_box_model.set_top_box_brand', 'user.manager')->where('users_id', $user_id->id);
            $all_set_top_box = SetTopBox::with('set_top_box_model.set_top_box_brand', 'user','subdistributor_user')->where('subdistributor', Entrust::user()->id); ;
        }

        return Datatables::of($all_set_top_box)
        // ->addColumn('Distributor', function ($all_set_top_box) {
        //     return $all_set_top_box->user->name;
        //     // if($all_set_top_box->user->manager == null) {
        //     //     return $all_set_top_box->user->name;
        //     // }
        //     // else{
        //     //     return $all_set_top_box->user->manager->name;
        //     // }
        // })
        ->addColumn('Subdistributor', function ($all_set_top_box) {
            if ($all_set_top_box->subdistributor_user != null)
                return $all_set_top_box->subdistributor_user->name;
            else
                return null;
            // if($all_set_top_box->user->manager != null) {
            //     return $all_set_top_box->user->name;
            // }
            // else{
            //     return 'N/A';
            // }
        })
        ->addColumn('Link', function ($all_set_top_box) {
            $action_view = '<a href="' . url('/settopboxes') . '/' . $all_set_top_box->id . '/edit' . '"' . 'class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
            <a class="btn btn-xs btn-danger" id="'.$all_set_top_box->id.'"
            data-toggle="modal" data-target="#confirm_delete">
            <i class="glyphicon glyphicon-trash"></i> Delete</a>'; 
            return $action_view; 
        })
        ->make(true);

    }

    public function editSetTopBox($id) {
        $set_top_box = SetTopBox::with('set_top_box_model')
        ->findOrFail($id);
        // pass set top box brand values
        $set_top_box_brand = SetTopBoxBrand::all();
        
        return view('SubscriberManagement::settopbox.edit_set_top_box')
        ->with('set_top_box', $set_top_box)
        ->with('set_top_box_brand', $set_top_box_brand);
    }

    public function editSetTopBoxProcess(Request $request, $id, RoleAccess $role_access) {
        $edit_set_top_box = SetTopBox::findOrFail($id);
        $edit_set_top_box->update($request->all());
        
        $edit_set_top_box->users_id = $role_access->getDistributor($request);
        $edit_set_top_box->subdistributor = $role_access->getSubdistributor($request);
        $edit_set_top_box->save();
        
        return back();
    }

}
