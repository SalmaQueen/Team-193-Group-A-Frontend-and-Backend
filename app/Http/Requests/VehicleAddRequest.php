<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleAddRequest extends FormRequest
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
            //
            "vehicle_registration_number"=>'required',
            "vehicle_nickname"=>'required',
            "driver_name"=>'required',
            "driver_id_number"=>'required',
            "driver_dl_number"=>'required',
            "conductor_name"=>'required',
            "conductor_id_number"=>'required',
            "conductor_permit_number"=>'required',
            "drivers_phone_number"=>'required',
            "confirm_drivers_phone_number"=>'required',
            "conductors_phone_number"=>'required',
            "daily_target"=>'required',
            "capacity"=>'required',
            "confirm_conductors_phone_number"=>'required',
        ];
    }
}
