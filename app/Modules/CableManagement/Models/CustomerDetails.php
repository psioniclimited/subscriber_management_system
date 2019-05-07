<?php

namespace App\Modules\CableManagement\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
    protected $table = 'customer_details';
    protected $primaryKey = 'id';

    public function setTimestampAttribute($value)
    {
        $this->attributes['timestamp'] = \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $value)->toDateTimeString();
    }
}
