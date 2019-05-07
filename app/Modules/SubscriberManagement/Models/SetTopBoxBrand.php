<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class SetTopBoxBrand extends Model
{
	// Table name
	protected $table = 'stb_brands';
	// Fillable field 
	protected $fillable = ['name'];

	public $timestamps = false;

	/**
     * Get the set top box models for the set top box brand.
     */
    public function set_top_box_model()
    {
        return $this->hasMany('App\Modules\SubscriberManagement\Models\SetTopBoxModel', 'stb_brands_id');
    }

	


}
