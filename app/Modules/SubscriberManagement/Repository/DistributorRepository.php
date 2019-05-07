<?php
namespace App\Modules\SubscriberManagement\Repository;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\RoleUser;
use App\Modules\User\Models\User;
use App\Modules\SubscriberManagement\Models\Card;
use Entrust;
class DistributorRepository{

  private $role; 
  private $subdistributor_role;
	
  function __construct(){
      $this->role = Role::where('name', 'distributor')->get()->first();
      $this->subdistributor_role = Role::where('name', 'sub_distributor')->get()->first();
  }

	public function allDistributorsByAttribute($attribute, $value, $columns = ['*']){
    $distributor = User::whereHas('roles', function ($query) {
      $query->where('id', '=', $this->role->id);
    })
    ->where($attribute, "LIKE", "%{$value}%")
    ->get($columns);

    return $distributor;
  }

  /**
   * Get all subdistributors
   * @param  [type] $attribute [description]
   * @param  [type] $value     [description]
   * @param  array  $columns   [description]
   * @return [type]            [description]
   */
  public function allSubDistributorsByAttribute($attribute, $value, $columns = ['*'], $distributor_id){
    $sub_distributor = User::whereHas('roles', function ($query) {
      $query->where('id', $this->subdistributor_role->id);
    })
    ->where('managed_by', $distributor_id)
    ->where($attribute, "LIKE", "%{$value}%")
    ->get($columns);

    return $sub_distributor;
  }

  /**
   * [distributorByCard -distributor by card]
   * @param  [type] $cards_id [description]
   * @return [type]           [description]
   */
  public function distributorByCard($cards_id){
    $distributor_by_card = Card::with('user')
    ->where('id', $cards_id)
    ->get();

    return response()->json($distributor_by_card);   
  }

  public function distributorAndSubDistributorByCard($cards_id){
    $distributor_by_card = Card::with('user.manager', 'subdistributor_user')
    ->where('id', $cards_id)
    ->get();

    return response()->json($distributor_by_card);   
  }
    
 

}