<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    //
    use softDeletes;
    public $fillable = ['sacco_name','amount','period','number_of_scans','package','created_by'];
}
