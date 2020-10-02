<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        "vehicle_registration_number",
        "vehicle_nickname",
        "driver_name",
        "driver_id_number",
        "driver_dl_number",
        "conductor_name",
        "conductor_id_number",
        "conductor_permit_number",
        "drivers_phone_number",
        "conductors_phone_number",
        "daily_target",
        "added_by_name",
        "added_by_email",
        "is_active",
        "is_approved",
        "sacco_name",
        "sacco_id",
        "capacity"
    ];
}
