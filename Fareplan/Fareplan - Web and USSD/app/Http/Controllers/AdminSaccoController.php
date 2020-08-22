<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddsaccoRequest;
use App\Payment;
use App\Sacco;
use App\User;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminSaccoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $total_transactions = Payment::where("is_approved",1)->get();
        $total_transactions = count($total_transactions);
        $total_saccos = Sacco::all();
        $total_saccos = count($total_saccos);
        $total_users = User::all();
        $total_users = count($total_users);
        $total_vehicles = Vehicle::all();
        $total_vehicles = count($total_vehicles);
        return view("admin.index",compact('total_transactions','total_saccos','total_users','total_vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("admin.sacco.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddsaccoRequest $request)
    {
        //
        if (Auth::check()){
            $user_name = Auth::user()->name;
            $user_email = Auth::user()->email;
            $sacco = $request->all();
            $sacco['added_by_name'] = $user_name;
            $sacco['added_by_email'] = $user_email;
            $sacco_exists = Sacco::whereRegistrationNumber($sacco['registration_number'])->get();
            if (count($sacco_exists)>0){
                Session::flash('sacco_exists','Sorry, a sacco with admission number '.$sacco['registration_number'].' already exists');
                return redirect()->back();
            }else{
                Sacco::create($sacco);
                User::create(['name'=>$sacco['chair_name'],'email'=>$sacco['chair_email_address'],'sacco_name'=>$sacco['sacco_name'],'password'=>bcrypt("pass123")]);
                Session::flash('added_sacco','Sacco added successfully');
                return redirect()->back();
            }
        }else{
            Session::flash('login_first','Adding sacco failed. Please login first to add a sacco.');
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
        $sacco = Sacco::findOrFail($id);


        return view('admin.sacco.edit', compact('sacco'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddsaccoRequest $request, $id)
    {
        if (Auth::check()){
            $user_name = Auth::user()->name;
            $user_email = Auth::user()->email;
            $sacco = $request->all();
            $sacco['added_by_name'] = $user_name;
            $sacco['added_by_email'] = $user_email;
            Sacco::findOrFail($id)->update($sacco);
            Session::flash('edited_sacco','The  sacco has been updated');
            return redirect('/saccos');
        }else{
            Session::flash('login_first','Updating sacco failed. Please login first to add a sacco.');
            return redirect()->back();
        }
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
        Sacco::findOrFail($id)->delete();
        Session::flash('deleted_sacco','Sacco deleted successfully.');
        return redirect("/saccos");
    }

    public function saccos()
    {
        //
        $saccos = Sacco::orderBy('sacco_name','asc')->paginate(50);
        return view('admin.sacco.saccos',compact('saccos'));
    }
}
