<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class PairHistory extends Model{

	protected $table = 'pair_history';

	/**
     * Get the card that owns the pair history
     */
    public function card(){
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\Card', 'cards_id');
    }
    
}
