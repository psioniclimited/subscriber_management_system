<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class SetTopBox extends Model
{
	// Table name
	protected $table = 'stbs';
	// Fillable fields
	protected $fillable = ['number', 'stb_models_id', 'customers_id', 'subdistributor'];

	public $timestamps = false;

	/**
     * Get the set top box model that owns the set top box.
     */
    public function set_top_box_model()
    {
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\SetTopBoxModel', 'stb_models_id');
    }

    /**
     * Get the customer that owns the set top box.
     */
    public function customer()
    {
        return $this->belongsTo('App\Modules\CableManagement\Models\Customer', 'customers_id');
    }

    /**
     * Get the user that owns the set top box.
     */
    public function user()
    {
        return $this->belongsTo('App\Modules\User\Models\User', 'users_id');
    }

    public function subdistributor_user(){
        return $this->belongsTo('App\Modules\User\Models\User', 'subdistributor');
    }

	


}
