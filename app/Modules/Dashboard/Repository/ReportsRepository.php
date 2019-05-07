<?php
namespace App\Modules\Dashboard\Repository;
use App\Modules\User\Models\RoleUser;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\CableManagement\Models\CustomerDetails;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use App\Modules\SubscriberManagement\Models\Card;

class ReportsRepository{
	
	/**
	 * [attributeCounter -count attribute]
	 * @param  [string] $attribute [attribute to be counted]
	 * @return [int]            [count value]
	 */
	public function attributeCounter($attribute) {
		if($attribute == 'distributor') {
			$attribute_counter = RoleUser::where('role_id', '=', 4)->count();
		}
		elseif ($attribute == 'customer') {
			$attribute_counter = Customer::count();
		}
		elseif ($attribute == 'user') {
			$attribute_counter = User::count();
		}
		elseif ($attribute == 'card') {
			$attribute_counter = Card::count();
		}
		elseif ($attribute == 'active_customer') {
			$attribute_counter = Customer::where('active', 1)
			->count();
		}
		elseif ($attribute == 'inactive_customer') {
			$attribute_counter = Customer::where('active', 0)
			->count();
		}

		return $attribute_counter;
	}

}