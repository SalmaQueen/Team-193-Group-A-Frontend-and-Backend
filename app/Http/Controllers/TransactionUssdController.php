<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Vehicle;
use Illuminate\Http\Request;

class TransactionUssdController extends Controller
{
    //
    public function ussd(){
        $sessionId   = $_POST["sessionId"];
        $serviceCode = $_POST["serviceCode"];
        $phoneNumber = $_POST["phoneNumber"];
        $text        = $_POST["text"];
        $items = explode("*",$text);


        if ($text == "") {
            // This is the first request. Note how we start the response with CON
            $menu = "CON Welcome to FarePlan:\n";
            $menu .= "Enter Amount";

        } elseif (count($items) == 1)//text = "123";[0]
        {
            if ($items[0] < 10) {
                $menu = "END Sorry, you can't transact with less than Ksh 10";
            }else {
                $menu = "CON Enter Vehicle Reg Number";
            }
        } elseif (count($items) == 2)//text = "100*KYG046";[1]
        {
            $vehicle_registration = $items[1];
            $vehicle_registration =str_replace(' ', '', $vehicle_registration);
            $vehicle_registration =strtoupper($vehicle_registration);

            $phoneNumber[0] = " ";
            $UserphoneNumber = trim($phoneNumber);
            $user_phone = $UserphoneNumber;

            $vehicle =Vehicle::where("vehicle_registration_number",$vehicle_registration)->get();

            $payment_exists = Payment::where(["vehicle_registration_number"=>$vehicle_registration,"PhoneNumber"=>$user_phone,"is_approved"=>0])->get();
            if (count($payment_exists)>0){
                return "END Sorry, you still have ".count($payment_exists)." payment to this vehicle to be approved";
            }

            $sacco= "";
            $registration_number= "";
            if (count($vehicle)>0){
                $menu = "CON Pay Ksh ".$items[0]." with Ksh 2 access charge to ".$vehicle_registration."?\n";
                $menu .= "1. Yes\n";
                $menu .= "2. Cancel";
            }else{
                $menu = "END Sorry, we did'nt find a vehicle with registration number ".$vehicle_registration.". Please try again with an accurate vehicle registration number\n";
            }
        } elseif (count($items) == 3)//text = "100*KYG046*1";[1]
        {
            if ($items[2] == "1") {
                //STK PUSH
                $phoneNumber[0] = " ";
                $UserphoneNumber = trim($phoneNumber);
                $user_phone = $UserphoneNumber;
                $user_amount = $items[0]+2;

                function generateBarcodeNumber() {
                    $number = mt_rand(100, 1000);
                    if (barcodeNumberExists($number)) {
                        return generateBarcodeNumber();
                    }
                    return $number;
                }
                function barcodeNumberExists($number) {
                    //return User::whereBarcodeNumber($number)->exists();
                }
                $gari = $items[1];
                $generated_code = generateBarcodeNumber();$AccountReference=strtoupper($gari);
                $gari = str_replace(' ', '', $gari);
                $pay_code = $gari.$generated_code;

                $mpesa= new \Safaricom\Mpesa\Mpesa();

                $BusinessShortCode="174379";
                $LipaNaMpesaPasskey="bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
                $TransactionType="CustomerPayBillOnline";
                $Amount=$user_amount;
                $PartyA="$user_phone";
                $PartyB="174379";
                $PhoneNumber="$user_phone";
                $CallBackURL="http://fareplan-demo.herokuapp.com/api/c2bcallback";
                $TransactionDesc="Bus Fare";
                $Remarks="Thank you for your service";
                $stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

                $json = json_decode($stkPushSimulation);
                if (isset($json->ResponseCode)){
                    $date = date("Y-m-d")." ".date("h:i:s");
                    $AccountReference = str_replace(' ', '', $items[1]);
                    $AccountReference = strtoupper($AccountReference);
                    $gari =Vehicle::where("vehicle_registration_number",$AccountReference)->get();
                    foreach ($gari as $item){
                        $sacco_jina = $item->sacco_name;
                    }
                    $payment = ["CheckoutRequestID"=>$json->CheckoutRequestID,"Amount"=>$user_amount,"PhoneNumber"=>$user_phone,
                        "sacco_name"=>$sacco_jina,"vehicle_registration_number"=>$AccountReference,"pay_code"=>$pay_code];
                    Payment::create($payment);
                    $menu =  "END Thank you. Please wait to enter MPESA pin\nYour Paycode is $generated_code";
                }elseif (isset($json->errorCode)){
                    $menu =  "END MPESA: ".$json->errorMessage;
                }else{
                    $menu =  "END An unknown error occurred. Please try again";
                }

            } else {
                $menu = "END You cancelled payment.\nThank you for using FarePlan.\nWe are here to take you cashless.\nYou health, is our priority.";
            }
        }
        header('Content-type: text/plain');
        echo $menu;
    }

}
