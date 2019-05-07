<?php

namespace App\Modules\User\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword,
        EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','territory_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role_user() {
        return $this->hasOne('App\Modules\User\Models\RoleUser');
    }

    public function territory(){
        return $this->belongsTo('App\Modules\CableManagement\Models\Territory');
    }

    public function customer(){
        return $this->hasMany('App\Modules\CableManagement\Models\Customer', 'users_id');
    }

    /** 
     * Get the cards for the user
     */
    public function card(){
        return $this->hasMany('App\Modules\SubscriberManagement\Models\Card', 'users_id');
    }

    public function roles(){
        return $this->belongsToMany('App\Modules\User\Models\Role', 'role_user', 'user_id', 'role_id');
    }


    /**
     * Get the superior user that owns the other users. Think this is not used
     */
    public function managed_by(){
        return $this->belongsTo('App\Modules\User\Models\User', 'managed_by');
    }

    /**
     * Get the superior user that owns the other users.
     * @return [type] [description]
     */
    public function manager(){
        return $this->belongsTo('App\Modules\User\Models\User', 'managed_by');
    }

    /**
     * Get the set top boxes for the user.
     */
    public function set_top_boxes()
    {
        return $this->hasMany('App\Modules\SubscriberManagement\Models\SetTopBox', 'users_id');
    }


    /**
     * Get customer notes for the user.
     */
    public function customer_notes(){
        return $this->hasMany('App\Modules\CableManagement\Models\CustomerNotes', 'users_id');
    }



    
}
