<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Subscribe;
use App\Transfer;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransactionStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = Vehicle::orderBy("vehicle_registration_number","desc")->pluck("vehicle_registration_number","vehicle_registration_number")->all();
        return view("pay.status",compact("vehicles"));
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
        $confirm_data = $request->all();
        $PhoneNumber = $confirm_data['PhoneNumber'];
        $VehicleRegNumber = $confirm_data['vehicle_registration_number'];
        $transaction = Payment::where('PhoneNumber',$PhoneNumber)->where('vehicle_registration_number',$VehicleRegNumber)->where('is_approved',0)->get();

        $request_id = "";
        $sacco = "";
        $reg_no = "";
        $amount = "";
        $id = "";
        if (count($transaction)>0){
            foreach ($transaction as $item){
                $request_id = $item->CheckoutRequestID;
                $sacco = $item->sacco_name;
                $reg_no = $item->vehicle_registration_number;
                $amount = $item->Amount-2;
                $id = $item->id;
            }
            $vehicle = Vehicle::where("vehicle_registration_number",$reg_no)->get();
            $driver_phone  = "";
            if (count($vehicle)>0){
                foreach ($vehicle as $driver){
                    $driver_phone = $driver->drivers_phone_number;
                }
                $driver_phone[0] = " ";
                $driver_phone = "254".trim($driver_phone);
            }

            $mpesa= new \Safaricom\Mpesa\Mpesa();
            $businessShortCode = "174379";
            $checkoutRequestID = $request_id;
            $password = "MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMjAwNDMwMTgzOTQ5";
            $timestamp = "20200430183949";
            $environment = env('MPESA_ENV');
            $STKPushRequestStatus=$mpesa->STKPushQuery($environment,$checkoutRequestID,$businessShortCode,$password,$timestamp);
            $decodedSTKPushRequestStatus = json_decode($STKPushRequestStatus);

            if (isset($decodedSTKPushRequestStatus->ResultCode)){
                if ($decodedSTKPushRequestStatus->ResultCode!=0){
                    Session::flash('transaction_failed',"MPESA: ".$decodedSTKPushRequestStatus->ResultDesc);
                    $approve_value = 2;
                    Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                    return redirect()->back();
                }else{
                    $subscriptions = Subscribe::where('CheckoutRequestID',$checkoutRequestID)->get();

                    if (count($subscriptions)>0){
                        $approve_value = 1;
                        Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                        Session::flash('transaction_approved','Payment approved');
                        $this->b2c($amount,$driver_phone,$reg_no,$sacco);
                        return redirect()->back();
                    }else{
                        $approve_value = 1;
                        Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                        Session::flash('transaction_approved','Payment approved');
                        $this->b2c($amount,$driver_phone,$reg_no,$sacco);
                        return redirect()->back();
                    }

                }
            }elseif (isset($decodedSTKPushRequestStatus->errorMessage)){
                Session::flash('transaction_failed','MPESA: '.$decodedSTKPushRequestStatus->errorMessage);
                return redirect()->back();
            }else{
                Session::flash('transaction_failed','FarePlan: An unknown error occurred');
                return redirect()->back();
            }
        }else{
            Session::flash('transaction_failed',"No pending payment for account $PhoneNumber to  $VehicleRegNumber. FarePlan.");
            return redirect()->back();
        }
    }

    public function b2c($amount,$driver_phone,$vehicleregno,$sacco_name)
    {
        $InitiatorName = "testapi";
        $SecurityCredential = "IyoeRnVvo1EuWLCPy2t4e6Cn+phW22BS1tXZp3x/bLiL24wHR97FIcHejIuqs7HUAHzlOyGrVLRKjdK+RqcURyoHufI9eeBINt4LxwS3jYe1U1BKUfORFZ6AqWidxwxwGVi9hZftua9hbwoOJHPLoGaVTxe7NkN7jIy9kv87TBUZTjfJGWtrL9aQToe5jDwH2XteQba71j6XtWAacQ6rx3/Eseeo5f1kf1zBwwsJt1y58N1LrhX6xzbNCTguE91MoNhYRNnGsZ4h427epFXthbNXDkE3f/WkofJFphlTCeOnjJ0mWjA6twbL7gPc9kpsus7neLvvTHNHTvyjbUeFlQ==";
        $CommandID = "BusinessPayment";
        $Amount = $amount;
        $PartyA = "600391";
        $PartyB = "254708374149";//Replace with driver's phone number
        $Remarks = "Test";
        $QueueTimeOutURL = "http://fareplan-demo.herokuapp.com/api/b2ccallback";
        $ResultURL = "http://fareplan-demo.herokuapp.com/api/b2ccallback";
        $Occasion = "";
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $b2cTransaction = $mpesa->b2c($InitiatorName, $SecurityCredential, $CommandID, $Amount, $PartyA, $PartyB,
            $Remarks, $QueueTimeOutURL, $ResultURL, $Occasion);

        $decodedJsonResponse = json_decode($b2cTransaction);
        $decodedJsonResponse = json_decode($decodedJsonResponse);

        if (isset($decodedJsonResponse->ResponseCode)){
            if ($decodedJsonResponse->ResponseCode == 0){
                Transfer::create(["vehicle_registration_number"=>$vehicleregno,"sacco_name"=>$sacco_name,"ConversationID"=>$decodedJsonResponse->ConversationID,"TransactionAmount"=>$amount,"ReceiverPhoneNumber"=>$driver_phone]);
                Session::flash('transaction_accepted',"Paid Ksh. $amount to $vehicleregno $sacco_name");
                return redirect()->back();
            }else{
                Session::flash('transaction_failed','Passenger paid but cash is not sent to driver. Ask FarePlan team to assist. Let the passenger go. FarePlan.');
                return redirect()->back();
            }
        }
    }

    public function mobile_b2c($amount,$driver_phone,$vehicleregno,$sacco_name)
    {
        $InitiatorName = "testapi";
        $SecurityCredential = "IyoeRnVvo1EuWLCPy2t4e6Cn+phW22BS1tXZp3x/bLiL24wHR97FIcHejIuqs7HUAHzlOyGrVLRKjdK+RqcURyoHufI9eeBINt4LxwS3jYe1U1BKUfORFZ6AqWidxwxwGVi9hZftua9hbwoOJHPLoGaVTxe7NkN7jIy9kv87TBUZTjfJGWtrL9aQToe5jDwH2XteQba71j6XtWAacQ6rx3/Eseeo5f1kf1zBwwsJt1y58N1LrhX6xzbNCTguE91MoNhYRNnGsZ4h427epFXthbNXDkE3f/WkofJFphlTCeOnjJ0mWjA6twbL7gPc9kpsus7neLvvTHNHTvyjbUeFlQ==";
        $CommandID = "BusinessPayment";
        $Amount = $amount;
        $PartyA = "600391";
        $PartyB = "254708374149";//Replace with driver's phone number
        $Remarks = "Fareplan";
        $QueueTimeOutURL = "http://fareplan-demo.herokuapp.com/api/b2ccallback";
        $ResultURL = "http://fareplan-demo.herokuapp.com/api/b2ccallback";
        $Occasion = "";
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $b2cTransaction = $mpesa->b2c($InitiatorName, $SecurityCredential, $CommandID, $Amount, $PartyA, $PartyB,
            $Remarks, $QueueTimeOutURL, $ResultURL, $Occasion);

        $decodedJsonResponse = json_decode($b2cTransaction);
        $decodedJsonResponse = json_decode($decodedJsonResponse);

        if (isset($decodedJsonResponse->ResponseCode)){
            if ($decodedJsonResponse->ResponseCode == 0){
                Transfer::create(["vehicle_registration_number"=>$vehicleregno,"sacco_name"=>$sacco_name,"ConversationID"=>$decodedJsonResponse->ConversationID,"TransactionAmount"=>$amount,"ReceiverPhoneNumber"=>$driver_phone]);
                return json_encode(["message"=>"Paid Ksh. $amount to $vehicleregno $sacco_name","value"=>0]);
            }else{
                return json_encode(["message"=>"Passenger paid but cash is not sent to driver. Ask FarePlan team to assist. Let the passenger go. FarePlan.","value"=>1]);
            }
        }
    }

    public function approve_by_phone(Request $request)
    {
        $paid_phone_number = $request->paid_phone_number;
//        $paid_phone_number = "254714359957";
        $conductor_mobile = $request->conductor_mobile;
//        $conductor_mobile = "254707378639";
        $conductor_mobile[0]=" ";
        $conductor_mobile[1]=" ";
        $conductor_mobile[2]=" ";
        $conductor_mobile = "0".trim($conductor_mobile);
        $paid_vehicle = Vehicle::where('conductors_phone_number',$conductor_mobile)->get();
        $vehicle_registration_number = "";
        if (isset($paid_vehicle)){
            if (count($paid_vehicle)>0){
               foreach ($paid_vehicle as $item){
                   $vehicle_registration_number = $item->vehicle_registration_number;
               }
            }
        }

        $PhoneNumber = $paid_phone_number;
        $VehicleRegNumber = $vehicle_registration_number;

        $transaction = Payment::where('PhoneNumber',$PhoneNumber)->where('vehicle_registration_number',$VehicleRegNumber)->where('is_approved',0)->get();
        if (isset($transaction)){
            $request_id = "";
            $sacco = "";
            $reg_no = "";
            $amount = "";
            $id = "";
            if (count($transaction)>0){
                foreach ($transaction as $item){
                    $request_id = $item->CheckoutRequestID;
                    $sacco = $item->sacco_name;
                    $reg_no = $item->vehicle_registration_number;
                    $amount = $item->Amount;
                    $id = $item->id;
                }
                $vehicle = Vehicle::where("vehicle_registration_number",$reg_no)->get();
                $driver_phone  = "";
                if (count($vehicle)>0){
                    foreach ($vehicle as $driver){
                        $driver_phone = $driver->drivers_phone_number;
                    }
                    $driver_phone[0] = " ";
                    $driver_phone = "254".trim($driver_phone);
                }

                $mpesa= new \Safaricom\Mpesa\Mpesa();
                $businessShortCode = "174379";
                $checkoutRequestID = $request_id;
                $password = "MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMjAwNDMwMTgzOTQ5";
                $timestamp = "20200430183949";
                $environment = env('MPESA_ENV');


                $STKPushRequestStatus=$mpesa->STKPushQuery($environment,$checkoutRequestID,$businessShortCode,$password,$timestamp);
                $decodedSTKPushRequestStatus = json_decode($STKPushRequestStatus);

                if (isset($decodedSTKPushRequestStatus->ResultCode)){
                    if ($decodedSTKPushRequestStatus->ResultCode!=0){
                        $approve_value = 2;
                        Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                        return json_encode(["message"=>$decodedSTKPushRequestStatus->ResultDesc,"value"=>1]);
                    }else{

                        $approve_value = 1;
                        Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                        return $this->mobile_b2c($amount,$driver_phone,$reg_no,$sacco);

//                        return json_encode(["message"=>$decodedSTKPushRequestStatus->ResultDesc,"value"=>1]);
                    }
                }elseif (isset($decodedSTKPushRequestStatus->errorMessage)){
                    $approve_value = 2;
                    Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                    return json_encode(["message"=>$decodedSTKPushRequestStatus->errorMessage,"value"=>1]);
                }else{
                    $approve_value = 2;
                    Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                    return json_encode(["message"=>"FarePlan: An unknown error occurred","value"=>1]);
                }
            }else{
                $approve_value = 2;
                Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                return json_encode(["message"=>"No pending payment for account $PhoneNumber to  $VehicleRegNumber. FarePlan.","value"=>1]);
            }
        }else{
            return json_encode(["message"=>"FarePlan: Payment does not exist","value"=>1]);
        }
    }

    public function approve_by_code(Request $request)
    {
        $pay_code = $request->pay_code;
//        $pay_code = "345";
//        $paid_phone_number = "254714359957";
        $conductor_mobile = $request->conductor_mobile;
//        $conductor_mobile = "254714359957";
        $conductor_mobile[0]=" ";
        $conductor_mobile[1]=" ";
        $conductor_mobile[2]=" ";
        $conductor_mobile = "0".trim($conductor_mobile);
        $paid_vehicle = Vehicle::where('conductors_phone_number',$conductor_mobile)->get();
        $vehicle_registration_number = "";
        if (isset($paid_vehicle)){
            if (count($paid_vehicle)>0){
                foreach ($paid_vehicle as $item){
                    $vehicle_registration_number = $item->vehicle_registration_number;
                }
            }
        }
        $VehicleRegNumber = $vehicle_registration_number;

        $transaction = Payment::where('pay_code',$VehicleRegNumber.$pay_code)->where('vehicle_registration_number',$VehicleRegNumber)->where('is_approved',0)->get();
        if (isset($transaction)){
            $request_id = "";
            $sacco = "";
            $reg_no = "";
            $amount = "";
            $id = "";
            if (count($transaction)>0){
                foreach ($transaction as $item){
                    $request_id = $item->CheckoutRequestID;
                    $sacco = $item->sacco_name;
                    $reg_no = $item->vehicle_registration_number;
                    $amount = $item->Amount;
                    $id = $item->id;
                }
                $vehicle = Vehicle::where("vehicle_registration_number",$reg_no)->get();
                $driver_phone  = "";
                if (count($vehicle)>0){
                    foreach ($vehicle as $driver){
                        $driver_phone = $driver->drivers_phone_number;
                    }
                    $driver_phone[0] = " ";
                    $driver_phone = "254".trim($driver_phone);
                }

                $mpesa= new \Safaricom\Mpesa\Mpesa();
                $businessShortCode = "174379";
                $checkoutRequestID = $request_id;
                $password = "MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMjAwNDMwMTgzOTQ5";
                $timestamp = "20200430183949";
                $environment = env('MPESA_ENV');


                $STKPushRequestStatus=$mpesa->STKPushQuery($environment,$checkoutRequestID,$businessShortCode,$password,$timestamp);
                $decodedSTKPushRequestStatus = json_decode($STKPushRequestStatus);

                if (isset($decodedSTKPushRequestStatus->ResultCode)){
                    if ($decodedSTKPushRequestStatus->ResultCode!=0){
                        $approve_value = 2;
                        Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                        return json_encode(["message"=>$decodedSTKPushRequestStatus->ResultDesc,"value"=>1]);
                    }else{

                        $approve_value = 1;
                        Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                        return $this->mobile_b2c($amount,$driver_phone,$reg_no,$sacco);

//                        return json_encode(["message"=>$decodedSTKPushRequestStatus->ResultDesc,"value"=>1]);
                    }
                }elseif (isset($decodedSTKPushRequestStatus->errorMessage)){
                    $approve_value = 2;
                    Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                    return json_encode(["message"=>$decodedSTKPushRequestStatus->errorMessage,"value"=>1]);
                }else{
                    $approve_value = 2;
                    Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                    return json_encode(["message"=>"FarePlan: An unknown error occurred","value"=>1]);
                }
            }else{
                $approve_value = 2;
                Payment::whereId($id)->update(["is_approved"=>$approve_value]);
                return json_encode(["message"=>"No pending payment for code $pay_code to  $VehicleRegNumber. FarePlan.","value"=>1]);
            }
        }else{
            return json_encode(["message"=>"FarePlan: Payment does not exist","value"=>1]);
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
        $approve_value = $request->except('_method','_token');
        $approve_value['is_approved'] = 1;
        Payment::whereId($id)->update($approve_value);
        Session::flash('transaction_approved','Payment approved');
        return redirect()->back();

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







//public function store(Request $request)
//{
//    //
//    $confirm_data = $request->all();
//    $PhoneNumber = $confirm_data['PhoneNumber'];
//    $VehicleRegNumber = $confirm_data['vehicle_registration_number'];
//    $transaction = Payment::where('PhoneNumber',$PhoneNumber)->where('is_approved',0)->get();
//
//    $request_id = "";
//    $sacco = "";
//    $reg_no = "";
//    $amount = "";
//    $id = "";
//    if (count($transaction)>0){
//        foreach ($transaction as $item){
//            $request_id = $item->CheckoutRequestID;
//            $sacco = $item->sacco_name;
//            $reg_no = $item->vehicle_registration_number;
//            $amount = $item->Amount-2;
//            $id = $item->id;
//        }
//        $vehicle = Vehicle::where("vehicle_registration_number",$reg_no)->get();
//        $driver_phone  = "";
//        if (count($vehicle)>0){
//            foreach ($vehicle as $driver){
//                $driver_phone = $driver->drivers_phone_number;
//            }
//            $driver_phone[0] = " ";
//            $driver_phone = "254".trim($driver_phone);
//        }
//
//        $mpesa= new \Safaricom\Mpesa\Mpesa();
//        $businessShortCode = "174379";
//        $checkoutRequestID = $request_id;
//        $password = "MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMjAwNDMwMTgzOTQ5";
//        $timestamp = "20200430183949";
//        $environment = env('MPESA_ENV');
//        $STKPushRequestStatus=$mpesa->STKPushQuery($environment,$checkoutRequestID,$businessShortCode,$password,$timestamp);
//        $decodedSTKPushRequestStatus = json_decode($STKPushRequestStatus);
//
//        if (isset($decodedSTKPushRequestStatus->ResultCode)){
//            if ($decodedSTKPushRequestStatus->ResultCode==0){
//                Session::flash('transaction_failed',"MPESA: ".$decodedSTKPushRequestStatus->ResultDesc);
//                $approve_value = 2;
//                Payment::whereId($id)->update(["is_approved"=>$approve_value]);
//                return redirect()->back();
//            }else{
//                $subscriptions = Subscribe::where('CheckoutRequestID',$checkoutRequestID)->get();
//                $so_far_scanned = "";
//                $number_of_scans = "";
//                $id = "";
//                if (count($subscriptions)>0){
//                    foreach ($subscriptions as $subscription){
//                        $so_far_scanned = $subscription->so_far_scanned;
//                        $number_of_scans = $subscription->number_of_scans;
//                        $id = $subscription->id;
//                    }
//                    if ($so_far_scanned<$number_of_scans){
//                        $x = Subscribe::findOrFail($id);
//                        $x->update(['so_far_scanned',$so_far_scanned+1]);
//
//                        $approve_value = 1;
//                        Payment::whereId($id)->update(["is_approved"=>$approve_value]);
//                        Session::flash('transaction_approved','Payment approved');
//                        $this->b2c($amount,$driver_phone,$reg_no,$sacco);
//                        return redirect()->back();
//                    }else{
//                        $approve_value = 2;
//                        Payment::whereId($id)->update(["is_approved"=>$approve_value]);
//                        Session::flash('transaction_failed','Subscription expired!');
//                        return redirect()->back();
//                    }
//
//                }else{
//                    $approve_value = 1;
//                    Payment::whereId($id)->update(["is_approved"=>$approve_value]);
//                    Session::flash('transaction_approved','Payment approved');
//                    $this->b2c($amount,$driver_phone,$reg_no,$sacco);
//                    return redirect()->back();
//                }
//
//            }
//        }elseif (isset($decodedSTKPushRequestStatus->errorMessage)){
//            Session::flash('transaction_failed','MPESA: '.$decodedSTKPushRequestStatus->errorMessage);
//            return redirect()->back();
//        }else{
//            Session::flash('transaction_failed','FarePlan: An unknown error occurred');
//            return redirect()->back();
//        }
//    }else{
//        Session::flash('transaction_failed',"No pending payment for account $PhoneNumber to  $VehicleRegNumber. FarePlan.");
//        return redirect()->back();
//    }
//}
