<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class SetTopBoxModel extends Model
{
	// Table name
	protected $table = 'stb_models';
	// Fillable field 
	protected $fillable = ['name', 'stb_brands_id'];

	public $timestamps = false;

	/**
     * Get the set top boxes for the set top box model.
     */
    public function set_top_box()
    {
        return $this->hasMany('App\Modules\SubscriberManagement\Models\SetTopBox', 'stb_models_id');
    }

    /**
     * Get the set top box brand that owns the set top box model.
     */
    public function set_top_box_brand()
    {
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\SetTopBoxBrand', 'stb_brands_id');
    }

	


}
