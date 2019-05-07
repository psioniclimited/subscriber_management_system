<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model {

	protected $table = 'email_history';
	protected $fillable = ['expired_time', 'sender_name', 'message_content', 'cards_id'];

    /**
     * Get the card that owns the email history
     */
    public function card(){
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\Card', 'cards_id');
    }

    public function setExpiredTimeAttribute($value) {
        $this->attributes['expired_time'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

}
