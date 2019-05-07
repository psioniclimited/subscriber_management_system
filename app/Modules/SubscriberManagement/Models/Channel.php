<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
	protected $fillable = ['name', 'description'];

	public function products(){
        return $this->belongsToMany('App\Modules\SubscriberManagement\Models\Product');
    }


}
