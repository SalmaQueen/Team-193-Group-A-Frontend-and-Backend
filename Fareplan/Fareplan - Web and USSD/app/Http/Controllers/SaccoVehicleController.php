<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleAddRequest;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SaccoVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::check()){
            $user_name = Auth::user()->name;
            $user_email = Auth::user()->email;
            $sacco_name = Auth::user()->sacco_name;
            $total_vehicles = Vehicle::where("sacco_name",$sacco_name)->get();
            $total_vehicles = count($total_vehicles);

            $active_vehicles = Vehicle::where(["sacco_name"=>$sacco_name,"is_active"=>1])->get();
            $active_vehicles = count($active_vehicles);

            $inactive_vehicles = $total_vehicles-$active_vehicles;
            $warned_vehicles = Vehicle::where(["sacco_name"=>$sacco_name,"is_active"=>4])->get();
            $warned_vehicles = count($warned_vehicles);

            return view("sacco.index",compact('total_vehicles','active_vehicles','inactive_vehicles','warned_vehicles'));
        }

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
        return view("sacco.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VehicleAddRequest $request)
    {
        //
        if (Auth::check()){
            $user_name = Auth::user()->name;
            $user_email = Auth::user()->email;
            $sacco_name = Auth::user()->sacco_name;
            $vehicle = $request->all();
            $vehicle['added_by_name'] = $user_name;
            $vehicle['added_by_email'] = $user_email;
            $vehicle['sacco_name'] = $sacco_name;

            $drivers_phone = $vehicle['drivers_phone_number'];
            $confirm_drivers_phone = $vehicle['confirm_drivers_phone_number'];
            $conductors_phone = $vehicle['conductors_phone_number'];
            $confirm_conductors_phone = $vehicle['confirm_conductors_phone_number'];
            $vehicle_exists = Vehicle::whereVehicleRegistrationNumber($vehicle['vehicle_registration_number'])->get();
            $conductor_exists = Vehicle::whereConductorsPhoneNumber($vehicle['conductors_phone_number'])->get();
            if (count($conductor_exists)>0){
                Session::flash('vehicle_exists','Sorry, a conductor with number '.$vehicle['conductors_phone_number'].' already exists');
                return redirect()->back();
            }
            if (trim($conductors_phone) !=trim($confirm_conductors_phone)){
                Session::flash('not_matching_drivers_phone','Sorry, Conductor\'s phone numbers don\'t match.');
                return redirect()->back();
            }

            if (trim($drivers_phone) !=trim($confirm_drivers_phone)){
                Session::flash('not_matching_drivers_phone','Sorry, Driver\'s phone numbers don\'t match.');
                return redirect()->back();
            }elseif (count($vehicle_exists)>0){
                Session::flash('vehicle_exists','Sorry, a vehicle with registration number '.$vehicle['vehicle_registration_number'].' already exists');
                return redirect()->back();
            }else{
                Vehicle::create($vehicle);
                Session::flash('added_vehicle','Vehicle added successfully');
                return redirect()->back();
            }
        }else{
            Session::flash('login_first','Adding vehicle failed. Please login first to add a vehicle.');
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
        $vehicle = Vehicle::findOrFail($id);


        return view('sacco.edit', compact('vehicle'));
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
        if (Auth::check()){
            $user_name = Auth::user()->name;
            $user_email = Auth::user()->email;
            $vehicle = $request->all();
            $vehicle['added_by_name'] = $user_name;
            $vehicle['added_by_email'] = $user_email;
            $drivers_phone = $vehicle['drivers_phone_number'];
            $confirm_drivers_phone = $vehicle['confirm_drivers_phone_number'];
            if (trim($drivers_phone) !=trim($confirm_drivers_phone)){
                Session::flash('not_matching_drivers_phone','Sorry, Drive\'s phone numbers don\'t match.');
                return redirect()->back();
            }else{
                Vehicle::findOrFail($id)->update($vehicle);
                Session::flash('edited_vehicle','The  vehicle has been updated');
                return redirect('/vehicles');
            }
        }else{
            Session::flash('login_first','Updating vehicle failed. Please login first to update a vehicle.');
            return redirect()->back();
        }
    }

    public function vehicle_actions(Request $request,$id){

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Vehicle::findOrFail($id)->delete();
        Session::flash('deleted_vehicle','Vehicle deleted successfully.');
        return redirect("/vehicles");
    }
    public function vehicles()
    {
        //

        if (Auth::check()){
            $sacco_name = Auth::user()->sacco_name;
            $vehicles = Vehicle::whereSaccoName($sacco_name)->orderBy('vehicle_registration_number','desc')->paginate(50);
            return view("sacco.vehicles",compact("vehicles"));
        }else{
            Session::flash('login_first','Please login first to load vehicles.');
            return redirect()->back();
        }
    }
}
