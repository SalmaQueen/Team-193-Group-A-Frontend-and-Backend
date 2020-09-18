<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Transfer;
use Illuminate\Http\Request;

class TransactionCallbackController extends Controller
{

    public function c2bcallback()
    {
        $callbackJSONData=file_get_contents('php://input');
        //$callbackJSONData='{"Body":{"stkCallback":{"MerchantRequestID":"16235-23028405-1","CheckoutRequestID":"ws_CO_010720201401022110","ResultCode":0,"ResultDesc":"The service request is processed successfully.","CallbackMetadata":{"Item":[{"Name":"Amount","Value":100.00},{"Name":"MpesaReceiptNumber","Value":"OG11E4IYYH"},{"Name":"Balance"},{"Name":"TransactionDate","Value":20200701140217},{"Name":"PhoneNumber","Value":254714359957}]}}}}';
        $jsonFile = fopen('c2bcallback.json', 'w');
        fwrite($jsonFile, $callbackJSONData);

        $json = json_decode($callbackJSONData);

        $CheckoutRequestID = $json->Body->stkCallback->CheckoutRequestID;
        $ResultCode = $json->Body->stkCallback->ResultCode;
        $CallbackMetadata = $json->Body->stkCallback->CallbackMetadata;

        $Amount = 0;
        $MpesaReceiptNumber = "";
        $PhoneNumber = "";
        $TransactionDate = "";
        foreach ($CallbackMetadata->Item as $item) {

            if ($item->Name == "Amount") {
                $Amount = $item->Value;
            } else if ($item->Name == "MpesaReceiptNumber") {
                $MpesaReceiptNumber = $item->Value;
            } else if ($item->Name == "PhoneNumber") {
                $PhoneNumber = $item->Value;
            } else if ($item->Name == "TransactionDate") {
                $TransactionDate = $item->Value;
            }
        }
        $update_value = ["MpesaReceiptNumber"=>$MpesaReceiptNumber,"Amount"=>$Amount,"PhoneNumber"=>$PhoneNumber,"TransactionDate"=>$TransactionDate];
        Payment::where("CheckoutRequestID",$CheckoutRequestID)->update($update_value);
        //return "It worked";
    }

    public function b2ccallback(){
//$payment = '{"Result":{"ResultType":0,"ResultCode":0,"ResultDesc":"The service request is processed successfully.",
//"OriginatorConversationID":"4348-25110042-1","ConversationID":"AG_20200703_000040c092ee6e1a65af","TransactionID":"OG201HCA8W",
//"ResultParameters":{"ResultParameter":[{"Key":"TransactionAmount","Value":2000},{"Key":"TransactionReceipt","Value":"OG201HCA8W"},
//{"Key":"B2CRecipientIsRegisteredCustomer","Value":"Y"},{"Key":"B2CChargesPaidAccountAvailableFunds","Value":-8030.00},
//{"Key":"ReceiverPartyPublicName","Value":"254708374149 - John Doe"},{"Key":"TransactionCompletedDateTime","Value":"02.07.2020 16:23:30"},
//{"Key":"B2CUtilityAccountAvailableFunds","Value":212318.00},{"Key":"B2CWorkingAccountAvailableFunds","Value":1100000.00}]},
//"ReferenceData":{"ReferenceItem":{"Key":"QueueTimeoutURL","Value":"https:\/\/internalsandbox.safaricom.co.ke\/mpesa\/b2cresults\/v1\/submit"}}}}';
        $payment = file_get_contents('php://input');
        $jsonFile = fopen('b2ccallback.json', 'w');
        fwrite($jsonFile, $payment);

        $json = json_decode($payment);
        $ConversationID = $json->Result->ConversationID;
        $ResultCode = $json->Result->ResultCode;
        $ResultParameters = $json->Result->ResultParameters->ResultParameter;

        $TransactionAmount = "";
        $TransactionReceipt = "";
        $ReceiverPartyPublicName = "";
        $TransactionCompletedDateTime = "";
        $B2CUtilityAccountAvailableFunds = "";
        $B2CWorkingAccountAvailableFunds = "";

        foreach ($json->Result->ResultParameters->ResultParameter as $item) {

            if ($item->Key == "TransactionAmount") {
                $TransactionAmount = $item->Value;
            } else if ($item->Key == "TransactionReceipt") {
                $TransactionReceipt = $item->Value;
            } else if ($item->Key == "ReceiverPartyPublicName") {
                $ReceiverPartyPublicName = $item->Value;
            }else if ($item->Key == "TransactionCompletedDateTime") {
                $TransactionCompletedDateTime = $item->Value;
            } else if ($item->Key == "B2CUtilityAccountAvailableFunds") {
                $B2CUtilityAccountAvailableFunds = $item->Value;
            } else if ($item->Key == "B2CWorkingAccountAvailableFunds") {
                $B2CWorkingAccountAvailableFunds = $item->Value;
            }
        }
$update_value = ["TransactionReceipt"=>$TransactionReceipt,"ReceiverPartyPublicName"=>$ReceiverPartyPublicName,
    "TransactionCompletedDateTime"=>$TransactionCompletedDateTime,"B2CUtilityAccountAvailableFunds"=>$B2CUtilityAccountAvailableFunds,
    "B2CWorkingAccountAvailableFunds"=>$B2CWorkingAccountAvailableFunds,"TransactionAmount"=>$TransactionAmount];
        Transfer::where("ConversationID",$ConversationID)->update($update_value);
        //return "It worked";
    }
}
