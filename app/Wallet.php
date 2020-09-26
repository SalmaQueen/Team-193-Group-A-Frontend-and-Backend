<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    public $fillable = ["vehicle_id","amount","withdraw_number","sent_to_number"];
}
