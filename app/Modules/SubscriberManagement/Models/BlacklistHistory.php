<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistHistory extends Model{

	protected $table = 'blacklist_history';

	/**
     * Get the card that owns the blacklist history
     */
    public function card(){
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\Card', 'cards_id');
    }
    
}
