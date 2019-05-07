<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class FingerprintHistory extends Model{

	protected $table = 'fingerprint_history';

	public function setExpiredTimeAttribute($value){
        $this->attributes['expired_time'] = \Carbon\Carbon::createFromFormat('d/m/Y g:i A', $value)->toDateTimeString();
    }

	/**
     * Get the card that owns the fingerprint history
     */
    public function card(){
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\Card', 'cards_id');
    }
    
}
