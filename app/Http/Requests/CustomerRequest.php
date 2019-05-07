<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Entrust;

class CustomerRequest extends Request {

    /**
     * Determine if the customer is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $validation = [
            'name' => 'required',
            'phone'   => 'required',
            'subscription_types_id' => 'required',
            'territory_id' => 'required',
        ];
        
        if(Entrust::hasRole('admin'))
            $validation['distributors_id'] = 'required';
        
        return $validation;
    }

}
