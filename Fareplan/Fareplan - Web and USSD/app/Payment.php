<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'CheckoutRequestID','MpesaReceiptNumber','Amount','PhoneNumber','TransactionDate','sacco_name','vehicle_registration_number','pay_code'
    ];
}
