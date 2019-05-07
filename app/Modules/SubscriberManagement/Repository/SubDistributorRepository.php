<?php
namespace App\Modules\SubscriberManagement\Repository;
use App\Modules\User\Models\User;
use Datatables;

class SubDistributorRepository{

  /**
   * [getSubDistributors- view sub distributors by distributor]
   * @param  [type] $attribute [to be matched with]
   * @param  [type] $value     [match value]
   * @param  array  $columns   [columns in table]
   * @return [json]            [specific sub distributors in json format]
   */
  public function getSubDistributors($attribute, $value, $columns = ['*']){
    $sub_distributors = User::where($attribute, $value);

    return Datatables::of($sub_distributors)
    ->addColumn('Link', function ($sub_distributors) {
            return '<a href="' . url('/sub_distributor') . '/' . $sub_distributors->id . '/edit' . '"' . 'class="btn btn-xs btn-primary">
                    <i class="glyphicon glyphicon-edit"></i> Edit</a>
                    <a class="btn btn-xs btn-danger" id="'.$sub_distributors->id.'"
                    data-toggle="modal" data-target="#confirm_delete">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                    </a>';
    })
    ->addColumn('Login', function ($sub_distributors) {
      if($sub_distributors->can_login == 0) {
        $login_button = '<a class="btn btn-xs bg-olive" id="'.$sub_distributors->id.'"
                        data-toggle="modal" data-target="#confirm_enable_login">
                        <i class="glyphicon glyphicon-edit"></i> Enable Login
                        </a>';  
      }
      else {
        $login_button = '<a class="btn btn-xs btn-warning" id="'.$sub_distributors->id.'"
                        data-toggle="modal" data-target="#confirm_disable_login">
                        <i class="glyphicon glyphicon-edit"></i> Disable Login
                        </a>'; 
      }
      
      return $login_button;
    })
    ->make(true);

  }

}