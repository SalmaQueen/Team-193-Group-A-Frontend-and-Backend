<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Subscribe;
use App\Subscription;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $SaccoSubscriptionPackages = Subscription::orderBy("sacco_name","asc")->pluck("package","id")->all();
        return view("subscribe.index",compact("SaccoSubscriptionPackages"));
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
        $id = $payment['id'];
        $subscriptions = Subscription::whereId($id)->get();
        $sacco_name = "";
        $sacco_id = "";
        $amount = "";
        $period = "";
        $number_of_scans = "";

        foreach ($subscriptions as $subscription){
            $sacco_name = $subscription->sacco_name;
            $amount = $subscription->amount+2;
            $period = $subscription->period;
            $number_of_scans = $subscription->number_of_scans;
            $sacco_id = $subscription->sacco_id;
        }

        $unapproved_transaction_exists = Payment::where(["is_approved"=>0,"PhoneNumber"=>$payment['PhoneNumber'],"sacco_id"=>$sacco_id,'is_subscription'=>0])->first();
        if (isset($unapproved_transaction_exists->id)){
            $unapproved_transaction_exists->update(["is_approved"=>3]);
//            Session::flash('transaction_failed','You still have '.count($unapproved_transaction_exists).' valid payment to be approved');
//            return redirect()->back();
        }
        $subscription_exists = Subscribe::where(["is_expired"=>0,"PhoneNumber"=>$payment['PhoneNumber'],"sacco_id"=>$sacco_id])->first();
        if (isset($subscription_exists->id)){
            Session::flash('transaction_failed',"You still have a valid subscription to this sacco. Continue enjoying your trips");
            return redirect()->back();
        }

        $mpesa= new \Safaricom\Mpesa\Mpesa();

        $payment['sacco_name'] = $sacco_name;

        if ($amount<10){
            Session::flash('transaction_failed','Please pay Ksh 10 or more.');
            return redirect()->back();
        }

        $paying_phone = $payment['PhoneNumber'];
        $paying_phone = trim($paying_phone);
        if ($paying_phone[0]==0){
            $paying_phone[0] = " ";
            $paying_phone = "254".trim($paying_phone);
        }
        if ($paying_phone[0]=="+"){
            $paying_phone[0] = " ";
            $paying_phone = trim($paying_phone);
        }
        if (strlen($paying_phone)!=12){
            Session::flash('transaction_failed',"$paying_phone is not a valid MPESA number");
            return redirect()->back();
        }
        $payment['PhoneNumber'] = $paying_phone;

        $BusinessShortCode = "174379";
        $LipaNaMpesaPasskey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $TransactionType = "CustomerPayBillOnline";
        $Amount = $amount;
        $PartyA = "254708374149";
        $PartyB = "174379";
        $PhoneNumber = $payment['PhoneNumber'];
        $CallBackURL = "http://fareplan-demo.herokuapp.com/api/c2bcallback";
        $AccountReference = "$sacco_name";
        $TransactionDesc = "Bus fare";
        $Remarks = "Thank you for paying with us";
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
                $payment["pay_code"] = $payment['sacco_name'].$generated_code;
                $payment["amount"] = $amount;
                $payment["period"] = $period;
                $payment["number_of_scans"] = $number_of_scans;
                $payment["sacco_id"] = $sacco_id;
                if ($payment['period']==7){
                    $payment["expires"] = Carbon::now()->addWeek()->toDateTimeString();
                }
                if ($payment['period']==30){
                    $payment["expires"] = Carbon::now()->addMonth()->toDateTimeString();
                }

                Subscribe::create($payment);
                $payment = ['CheckoutRequestID'=>$payment['CheckoutRequestID'],'Amount'=>$amount,
                    'PhoneNumber'=>$payment['PhoneNumber'],'sacco_name'=>$sacco_name,'pay_code'=>$payment["pay_code"],'is_subscription'=>1,'sacco_id'=>$sacco_id];
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
}
