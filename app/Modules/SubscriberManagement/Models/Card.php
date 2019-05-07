<?php

namespace App\Modules\SubscriberManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
	protected $fillable = ['card_id', 'set_top_box_sn', 'set_top_box_model', 'subdistributor'];

	/** 
     * Get the customer that owns the card
     */
    public function customers(){
		return $this->belongsTo('App\Modules\CableManagement\Models\Customer', 'customers_id');
	}

    /**
     * Get the user that owns the card 
     */
	public function user(){
        return $this->belongsTo('App\Modules\User\Models\User', 'users_id');
    }

    public function subdistributor_user(){
        return $this->belongsTo('App\Modules\User\Models\User', 'subdistributor');
    }

    /**
     * Get the message history for the card
    */
    public function message_history(){
        return $this->hasMany('App\Modules\SubscriberManagement\Models\MessageHistory', 'cards_id');
    }

    /**
     * Get the cardEntitlementHistory for the card
    */
    public function card_entitlement_history(){
        return $this->hasMany('App\Modules\SubscriberManagement\Models\CardEntitlementHistory', 'cards_id');
    }

    /**
     * Get the black history for the card
    */
    public function blacklist_history(){
        return $this->hasMany('App\Modules\SubscriberManagement\Models\BlacklistHistory', 'cards_id');
    }

    public function last_blacklist_history(){
        return $this->hasOne('App\Modules\SubscriberManagement\Models\BlacklistHistory', 'cards_id')->latest('expired_time');   
    }

    /**
     * Get the pair history for the card
    */
    public function pair_history(){
        return $this->hasMany('App\Modules\SubscriberManagement\Models\PairHistory', 'cards_id');
    }

    /**
     * Get the fingerprint history for the card
    */
    public function fingerprint_history(){
        return $this->hasMany('App\Modules\SubscriberManagement\Models\FingerprintHistory', 'cards_id');
    }
    
    /**
     * Get the email history for the card
    */
    public function email_history(){
        return $this->hasMany('App\Modules\SubscriberManagement\Models\EmailHistory', 'cards_id');
    }



}
