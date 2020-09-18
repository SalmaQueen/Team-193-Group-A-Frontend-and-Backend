<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MpesaC2bController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = Vehicle::orderBy("vehicle_registration_number","desc")->pluck("vehicle_registration_number","vehicle_registration_number")->all();
        return view("pay.index",compact("vehicles"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $payment = $request->all();
        $unapproved_transaction_exists = Payment::where(["is_approved"=>0,"PhoneNumber"=>$payment['PhoneNumber'],"vehicle_registration_number"=>$payment['vehicle_registration_number']])->get();
        if (count($unapproved_transaction_exists)>0){
            Session::flash('transaction_failed','You still have '.count($unapproved_transaction_exists).' valid payment to be approved');
            return redirect()->back();
        }

        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $vehicle_registration = $payment['vehicle_registration_number'];
        $vehicle = Vehicle::whereVehicleRegistrationNumber($vehicle_registration)->get();

        foreach ($vehicle as $item){
            $vehicle = $item->sacco_name;
        }
        $payment['sacco_name'] = $vehicle;

        $payment['sacco_name'] = $vehicle;
        if ($payment['Amount']<10){
            Session::flash('transaction_failed','Please pay Ksh 10 or more.');
            return redirect()->back();
        }

        $BusinessShortCode = "174379";
        $LipaNaMpesaPasskey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $TransactionType = "CustomerPayBillOnline";
        $Amount = $payment['Amount'];
        $PartyA = "254708374149";
        $PartyB = "174379";
        $PhoneNumber = $payment['PhoneNumber'];
        $CallBackURL = "http://ab7eb01be04a.ngrok.io/api/c2bcallback";
        $AccountReference = "$vehicle_registration";
        $TransactionDesc = "Bus fare";
        $Remarks = "Thank you for shopping with us";
        $stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType,
            $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
        $decodedJsonResponse = json_decode($stkPushSimulation);

        if (isset($decodedJsonResponse->ResponseCode)){
                    $ResponseCode = $decodedJsonResponse->ResponseCode;
                    $RequestID = $decodedJsonResponse->CheckoutRequestID;

        if ($ResponseCode==0){
            $payment['ResultCode'] = $ResponseCode;
            $payment['CheckoutRequestID'] = $RequestID;

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
            $generated_code = generateBarcodeNumber();
            $payment["pay_code"] = $payment['vehicle_registration_number'].$generated_code;


            Payment::create($payment);
            Session::flash('transaction_accepted','Request successful, please check your phone to enter MPESA pin. Your payment code is '.$generated_code);
            return redirect()->back();
        }else{
            Session::flash('transaction_failed','Your request has been declined with error: '.$decodedJsonResponse->ResponseDescription);
            return redirect()->back();
        }
        }elseif (isset($decodedJsonResponse->errorMessage)){
            Session::flash('transaction_failed',$decodedJsonResponse->errorMessage);
            return redirect()->back();
        }else{
            Session::flash('transaction_failed','An unknown error occurred, please try again');
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pay_by_phone(Request $request){
        $payment = $request->all();
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
        $generated_code = generateBarcodeNumber();
        $payment["pay_code"] = $payment['vehicle_registration_number'].$generated_code;
        $pay_code = $payment["pay_code"];

        $unapproved_transaction_exists = Payment::where(["is_approved"=>0,"PhoneNumber"=>$payment['PhoneNumber'],"vehicle_registration_number"=>$payment['vehicle_registration_number']])->get();
        if (count($unapproved_transaction_exists)>0){
            return json_encode(["message"=>"You still have ".count($unapproved_transaction_exists)." valid payment to be approved","value"=>1]);
        }

        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $vehicle_registration = $payment['vehicle_registration_number'];
        $vehicle = Vehicle::whereVehicleRegistrationNumber($vehicle_registration)->get();

        foreach ($vehicle as $item){
            $vehicle = $item->sacco_name;
        }
        $payment['sacco_name'] = $vehicle;
        if ($payment['Amount']<10){
            return json_encode(["message"=>"Please pay Ksh 10 or more","value"=>1]);
        }

        $BusinessShortCode = "174379";
        $LipaNaMpesaPasskey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $TransactionType = "CustomerPayBillOnline";
        $Amount = $payment['Amount'];
        $PartyA = "254708374149";
        $PartyB = "174379";
        $PhoneNumber = $payment['PhoneNumber'];
        $CallBackURL = "http://ab7eb01be04a.ngrok.io/api/c2bcallback";
        $AccountReference = "$vehicle_registration";
        $TransactionDesc = "Bus fare";
        $Remarks = "Thank you for shopping with us";
        $stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType,
            $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
        $decodedJsonResponse = json_decode($stkPushSimulation);

        if (isset($decodedJsonResponse->ResponseCode)){
            $ResponseCode = $decodedJsonResponse->ResponseCode;
            $RequestID = $decodedJsonResponse->CheckoutRequestID;

            if ($ResponseCode==0){
                $payment['ResultCode'] = $ResponseCode;
                $payment['CheckoutRequestID'] = $RequestID;

                Payment::create($payment);
                return json_encode(["message"=>"Request successful, please check your phone to enter MPESA pin","value"=>0,"pay_code"=>$generated_code]);
            }else{
                return json_encode(["message"=>"Your request has been declined with error: $decodedJsonResponse->ResponseDescription","value"=>1]);
            }
        }elseif (isset($decodedJsonResponse->errorMessage)){
            return json_encode(["message"=>$decodedJsonResponse->errorMessage,"value"=>1]);
        }else{
            return json_encode(["message"=>"An unknown error occurred, please try again","value"=>1]);
        }

    }

    public function load_vehicles(Request $request){
        $vehicles = Vehicle::orderBy("id")->get();
        return $vehicles;
    }

    public function load_payments(Request $request){
        $payments = Payment::orderBy("id")->get();
        return $payments;
    }
}
