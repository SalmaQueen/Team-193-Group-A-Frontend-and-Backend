<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Sacco extends Model
{
    use softDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        "sacco_name",
        "registration_number",
        "route_name",
        "route_number",
        "chair_name",
        "chair_id_number",
        "chair_email_address",
        "chair_phone_number",
        "added_by_name",
        "added_by_email",
        "is_active"
    ];
}
