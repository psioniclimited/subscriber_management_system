<?php

namespace App\Modules\CableManagement\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotes extends Model{
	
    protected $table = 'customer_notes';
    protected $fillable = ['note', 'customers_id', 'users_id'];

    /**
     * Get the user that owns the customer notes.
     */
    public function user(){
        return $this->belongsTo('App\Modules\User\Models\User', 'users_id');
    }
    
}
