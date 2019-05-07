<?php

namespace App\Modules\SubscriberManagement\Repository;
use App\Modules\User\Models\Role;
use App\Modules\SubscriberManagement\Models\SetTopBoxModel;
use App\Modules\SubscriberManagement\Models\SetTopBox;
use App\Modules\SubscriberManagement\Models\SetTopBoxBrand;
use App\Modules\User\Models\User;

class SetTopBoxRepository{

	/**
   * [allSetTopBoxModels -get set top box models from db]
   * @return [type] [description]
   */
  public function allSetTopBoxModels($attribute, $value, $columns = ['*']){

    $set_top_box_models = SetTopBoxModel::where($attribute, "LIKE", "%{$value}%")
                                        ->get($columns);
    return $set_top_box_models;  

  }

  public function allSetTopBoxes($attribute, $value, $columns = ['*']){
    $set_top_boxes = SetTopBox::where($attribute, "LIKE", "%{$value}%")
                              ->whereDoesntHave('customer')
                              ->get($columns);
    return $set_top_boxes;  

  }

  public function allSetTopBoxesForDistributorByAttribute($attribute, $value, $columns = ['*'], $distributor_id){
        // $sub_distributors = User::where('managed_by', $distributor_id)->lists('id');
        // $sub_distributors->push($distributor_id);
        return SetTopBox::where($attribute, "LIKE", "%{$value}%")
                              ->whereDoesntHave('customer')
                              ->where('users_id', $distributor_id)
                              // ->whereIn('users_id', $sub_distributors)
                              ->get($columns);
    }

    public function allSetTopBoxesForSubdistributorByAttribute($attribute, $value, $columns = ['*'], $sub_distributor_id) {
      return SetTopBox::where($attribute, "LIKE", "%{$value}%")
                              ->whereDoesntHave('customer')
                              ->where('subdistributor', $sub_distributor_id)
                              // ->where('users_id', $sub_distributor_id)
                              ->get($columns);
    }

  /**
   * [allSetTopBoxBrands -get set top box brands]
   * @param  [type] $attribute [description]
   * @param  [type] $value     [description]
   * @param  array  $columns   [description]
   * @return [type]            [description]
   */
  public function allSetTopBoxBrands($attribute, $value, $columns = ['*']){

    $set_top_box_brands = SetTopBoxBrand::where($attribute, "LIKE", "%{$value}%")
                                        ->get($columns);
    return $set_top_box_brands;  

  }

  public function allSetTopBoxModelsByBrand($attribute, $search_term, $value_term, $columns = ['*']){
    $set_top_box_models_by_brand = SetTopBoxModel::where($attribute, "LIKE", "%{$search_term}%")
    ->where('stb_brands_id', '=', $value_term)
    ->get($columns);

    return $set_top_box_models_by_brand;
  }

  /**
   * [allSetTopBoxDetails -get set top box details]
   * @param  [type] $set_top_box_id [description]
   * @return [json]                 [description]
   */
  public function allSetTopBoxDetails($set_top_box_id){
    // $set_top_box = SetTopBox::with('set_top_box_model', 'user.manager')
    //                           ->where('id', $set_top_box_id)
    //                           ->get();
    $set_top_box = SetTopBox::with('set_top_box_model', 'user', 'subdistributor_user')
                              ->where('id', $set_top_box_id)
                              ->get();                          
    return response()->json($set_top_box);  
  }


    
 

}