<?php

namespace App\Modules\CableManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	protected $table = 'customers';
	protected $primaryKey = 'customers_id';

	protected $fillable = ['name', 'phone', 'email', 'address', 'active', 'nid', 'passport', 'subscription_types_id', 'users_id', 'territory_id', 'sectors_id', 'roads_id', 'houses_id', 'subdistributor'];

	// public function customer_details()
 //    {
 //        return $this->hasMany('App\Modules\CableManagement\Models\CustomerDetails');
 //    }
 
 	public function house(){
 		return $this->belongsTo('App\Modules\CableManagement\Models\House', 'houses_id');
 	}

 	/** 
 	 * Get the cards for the customer
 	 */
 	public function cards(){
        return $this->hasMany('App\Modules\SubscriberManagement\Models\Card','customers_id');

    }

    public function card(){
        return $this->hasOne('App\Modules\SubscriberManagement\Models\Card', 'customers_id');

    }

    public function user(){
        return $this->belongsTo('App\Modules\User\Models\User', 'users_id');
    }

    public function subdistributor_user(){
        return $this->belongsTo('App\Modules\User\Models\User', 'subdistributor');
    }

    /**
     * Get the set top boxes for the customer.
     */
    public function set_top_boxes()
    {
        return $this->hasMany('App\Modules\SubscriberManagement\Models\SetTopBox', 'customers_id');
    }
}
