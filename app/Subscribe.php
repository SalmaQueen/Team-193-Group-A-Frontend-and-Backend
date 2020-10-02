<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscribe extends Model
{
    //
    use softDeletes;
    protected $dates = ['deleted_at'];
    public $fillable = ['sacco_name','sacco_id','is_expired','amount','period','number_of_scans','PhoneNumber','CheckoutRequestID','expires','daily_track','pay_code','so_far_scanned'];
}
