<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Role;
use App\Sacco;
use App\Subscribe;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SaccoSubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::check()) {
            $user_email = Auth::user()->email;
            $sacco_name = Auth::user()->sacco_name;
            $subscriptions = Subscription::where("sacco_name",$sacco_name)->get();
            return view("sacco.subscription.index",compact('subscriptions'));
        }
        Session::flash('login_first','Please login to create a subscription');
        return redirect()->back();

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

        if (Auth::check()){
            $subscription = $request->all();
            $user_email = Auth::user()->email;
            $subscription['created_by'] = $user_email;
            $saccos = Sacco::whereChairEmailAddress($user_email)->get();
            $sacco_name = "";
            $sacco_id = "";
            foreach ($saccos as $sacco){
                $sacco_name = $sacco->sacco_name;
                $sacco_id = $sacco->id;
            }
            $subscription['sacco_name'] = $sacco_name;
            $subscription['sacco_id'] = $sacco_id;
            $subscription['package'] = $sacco_name." | "."Ksh ".$subscription['amount']." | ".$subscription['period']." days."." (".$subscription['number_of_scans']." scans per day)";
            Subscription::create($subscription);
            Session::flash('subscription_created','Subscription created successfully');
            return redirect()->back();
        }
        Session::flash('login_first','Please login to create a subscription');
        return redirect()->back();
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
        $subscription = Subscription::findOrFail($id);
        return view('sacco.subscription.edit',compact('subscription'));
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
        Subscription::whereId($id)->update($request->except('_method','_token'));
        Session::flash('updated_subscription','Subscription updated successfully.');
        return redirect("/subscriptions");
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
        Subscription::findOrFail($id)->delete();
        Session::flash('deleted_subscription','Subscription deleted successfully.');
        return redirect("/subscriptions");
    }


    public function subscribers()
    {
        //
        if (Auth::check()) {
            $sacco_name = Auth::user()->sacco_name;
            $user_email = Auth::user()->email;
            $Sacco = Sacco::where("chair_email_address",$user_email)->first();
            $SaccoID = "";
            if (isset($Sacco->id)){
                $SaccoID = $Sacco->id;
            }

            $subscribers = Subscribe::where("sacco_name",$sacco_name)->get();
            return view("sacco.payments.subscriptions",compact('subscribers'));
        }
    }

    public function payments()
    {
        //
        if (Auth::check()) {
            $sacco_name = Auth::user()->sacco_name;
            $payments = Payment::where(["sacco_name"=>$sacco_name,"is_approved"=>1])->get();
            return view("sacco.payments.payments",compact('payments'));
        }
    }
}
