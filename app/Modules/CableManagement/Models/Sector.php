<?php

namespace App\Modules\CableManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
	protected $hidden = array('created_at', 'updated_at');
	
	public function road()
    {
        return $this->hasMany('App\Modules\CableManagement\Models\Road', 'sectors_id');
    }

    public function territory(){
 		return $this->belongsTo('App\Modules\CableManagement\Models\Territory', 'territory_id');
 	}



}
