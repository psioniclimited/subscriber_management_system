<?php
namespace App\Modules\SubscriberManagement\Repository;
use App\Modules\SubscriberManagement\Models\Product;
use Auth;

class ProductRepository{

	/**
	 * [getProducts - get product collection by user]
	 * @return [collection] [collection of products]
	 */
	public function getProducts(){
		$user = Auth::user();
		if($user->hasRole('admin')){
			$products = Product::all();
		}
		else if($user->hasRole('distributor') || $user->hasRole('sub_distributor')){
			$products = Product::where('days', '!=', 0)->get();
		}
		return $products;
	}
}