<?php

namespace App\Modules\CableManagement\Models;

use Illuminate\Database\Eloquent\Model;
class Territory extends Model
{
    protected $table = 'territory';
    protected $hidden = array('created_at', 'updated_at');

    public function sector()
    {
        return $this->hasMany('App\Modules\CableManagement\Models\Sector');
    }

    public function user(){
        return $this->hasMany('App\Modules\User\Models\User');
    }

   
}
