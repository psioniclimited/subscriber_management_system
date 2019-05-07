<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class CardEntitlementHistory extends Model
{
	protected $table = 'card_entitlement_history';
    protected $fillable = ['cards_id', 'operations_id', 'products_id', 'start_time', 'end_time', 'execution_status', 'unentitled', 'messages_id'];

	public function setStartTimeAttribute($value){
        $this->attributes['start_time'] = \Carbon\Carbon::createFromFormat('d/m/Y g:i A', $value)->toDateTimeString();
    }

    public function setEndTimeAttribute($value){
        $this->attributes['end_time'] = \Carbon\Carbon::createFromFormat('d/m/Y g:i A', $value)->toDateTimeString();
    }

    /**
     * Get the card that owns the card entitlement history 
     */
    public function card(){
    	return $this->belongsTo('App\Modules\SubscriberManagement\Models\Card', 'cards_id');
    }

    public function product(){
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\Product', 'products_id');
    }

    public function operation(){
        return $this->belongsTo('App\Modules\SubscriberManagement\Models\Operation', 'operations_id');
    }
}
