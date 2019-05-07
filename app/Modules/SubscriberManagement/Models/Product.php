<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable = ['name', 'product_id', 'days'];

	public function channels(){
        return $this->belongsToMany('App\Modules\SubscriberManagement\Models\Channel', 'products_has_channels', 'products_id', 'channels_id');
    }


}
