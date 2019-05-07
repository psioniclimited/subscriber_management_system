<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CardRequest extends Request {

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
        return [
        	'card_id' => 'unique:cards|required',
            'distributors_id' => 'required',
        ];
    }

}
