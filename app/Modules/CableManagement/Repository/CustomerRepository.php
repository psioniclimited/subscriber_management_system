<?php
namespace App\Modules\CableManagement\Repository;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Models\SetTopBox;

class CustomerRepository{

	/**
	 * [getCustomerDetails- view customer related data]
	 * @param  [type] $attribute [to be matched with]
	 * @param  [type] $value     [match value]
	 * @param  array  $columns   [columns in table]
	 * @return [type]            [collection]
	 */
	public function getCustomerDetails($attribute, $value, $columns = ['*']){
        $customer_details = Customer::with('user', 'house')
        ->where($attribute, $value)
        ->get($columns);
    	return $customer_details;
	}

	/**
	 * [getCardDetails view card id on view]
	 * @param  [type] $attribute [to be matched with]
	 * @param  [type] $value     [match value]
	 * @param  array  $columns   [columns in table]
	 * @return [type]            [collection]
	 */
	public function getCardDetails($attribute, $value, $columns = ['*']){
		$card_details = Card::where($attribute, $value)
        ->get($columns);
        return $card_details;

	}

	/**
	 * [deleteCustomer - delete customer from db]
	 * @param  [int] $id [customer id]
	 * @return [redirect]     [redirect to all customers list view]
	 */
	public function deleteCustomer($id){
		// Get customer
		$delete_customer = Customer::find($id);
		// Get customer id
		$customer_id = $delete_customer->customers_id;
		// Update customer id in cards table  
		$card = Card::where('customers_id', $customer_id)
		->update(['customers_id' => null]);
		// Update customer id in set top box table
		$set_top_box = SetTopBox::where('customers_id', $customer_id)
		->update(['customers_id' => null]);
		// Delete customer from db
        $delete_customer->delete();
        return redirect('allcustomers');
	}



}