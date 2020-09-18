<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        "vehicle_registration_number",
        "sacco_name",
        "ConversationID",
        "TransactionAmount",
        "TransactionReceipt",
        "ReceiverPartyPublicName",
        "ReceiverPhoneNumber",
        "TransactionCompletedDateTime",
        "B2CUtilityAccountAvailableFunds",
        "B2CWorkingAccountAvailableFunds"
    ];
}
