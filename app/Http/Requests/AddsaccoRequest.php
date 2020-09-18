<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddsaccoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "sacco_name"=>'required',
            "registration_number"=>'required',
            "route_name"=>'required',
            "route_number"=>'required',
            "chair_name"=>'required',
            "chair_id_number"=>'required',
            "chair_email_address"=>'required',
            "chair_phone_number"=>'required'
        ];
    }
}
