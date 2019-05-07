<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class MessageHistory extends Model{

	protected $fillable = ['message_type', 'show_time_length', 'show_times', 'expired_time', 'text', 'coverage_rate', 'cards_id'];

	protected $table = 'message_history';


	public function setExpiredTimeAttribute($value){
        $this->attributes['expired_time'] = \Carbon\Carbon::createFromFormat('d/m/Y g:i A', $value)->toDateTimeString();
    }

    /**
     * Get the card that owns the message history
     */
    public function card(){
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\Card', 'cards_id');
    }
	
}
