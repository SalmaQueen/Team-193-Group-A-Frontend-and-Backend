@extends('layouts.sacco')
@section('content')
    <h1 class="text-muted text-center">ADD A VEHICLE</h1>
    {{Form::open(['action'=>'SaccoVehicleController@store','method'=>'POST'])}}
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            @if(Session::has('added_vehicle'))
                <div class="alert alert-success">
                    <ul>
                        <li>{{session('added_vehicle')}}</li>
                    </ul>
                </div>
            @endif
            @if(Session::has('login_first'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{{session('login_first')}}</li>
                    </ul>
                </div>
            @endif
            @if(Session::has('vehicle_exists'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{{session('vehicle_exists')}}</li>
                    </ul>
                </div>
            @endif
            @if(Session::has('not_matching_drivers_phone'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{{session('not_matching_drivers_phone')}}</li>
                    </ul>
                </div>
            @endif
            @include('includes.form_error')
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-plus"></i></span>
                <div class="info-box-content">
                    <div class="form-group">
                        {!! Form::hidden('added_by_name')!!}
                        {!! Form::hidden('added_by_email')!!}
                        {!! Form::hidden('sacco_name')!!}
                        <div class="row">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <label for="">Vehicle. Reg. NO:</label>
                                    {!! Form::text('vehicle_registration_number', null, ['class'=>'form-control','placeholder' => 'Vehicle registration number'])!!}
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <label for="">Nickname:</label>
                                    {!! Form::text('vehicle_nickname', null, ['class'=>'form-control','placeholder' => 'Vehicle nickname'])!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-2"></div>
    </div>


    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-plus"></i></span>
                <div class="info-box-content">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="">Driver name:</label>
                                {!! Form::text('driver_name', null, ['class'=>'form-control','placeholder' => 'Driver name'])!!}
                            </div>
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="">ID number:</label>
                                {!! Form::text('driver_id_number', null, ['class'=>'form-control','placeholder' => 'Driver ID Number'])!!}
                            </div>
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="">DL number:</label>
                                {!! Form::text('driver_dl_number', null, ['class'=>'form-control','placeholder' => 'Driver DL Number'])!!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-plus"></i></span>
                <div class="info-box-content">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="">Conductor name:</label>
                                {!! Form::text('conductor_name', null, ['class'=>'form-control','placeholder' => 'Conductor name'])!!}
                            </div>
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="">ID Number:</label>
                                {!! Form::text('conductor_id_number', null, ['class'=>'form-control','placeholder' => 'Conductor ID Number'])!!}
                            </div>
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="">Permit number:</label>
                                {!! Form::text('conductor_permit_number', null, ['class'=>'form-control','placeholder' => 'Conductor Permit Number'])!!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-plus"></i></span>
                <div class="info-box-content">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="">Driver's Phone:</label>
                                {!! Form::text('drivers_phone_number', null, ['class'=>'form-control','placeholder' => 'Driver\'s Phone'])!!}
                            </div>
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="">Confirm D.Phone:</label>
                                {!! Form::text('confirm_drivers_phone_number', null, ['class'=>'form-control','placeholder' => 'Confirm Driver\'s Phone'])!!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-plus"></i></span>
                <div class="info-box-content">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="">Conductor's phone:</label>
                                {!! Form::text('conductors_phone_number', null, ['class'=>'form-control','placeholder' => 'Conductor\'s phone'])!!}
                            </div>
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="">Confirm Cond's phone:</label>
                                {!! Form::text('confirm_conductors_phone_number', null, ['class'=>'form-control','placeholder' => 'Conductor\'s phone'])!!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-2"></div>
    </div>


    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4">
                        <label for="">Capcity:</label>
                        {!! Form::text('capacity', null, ['class'=>'form-control','placeholder' => 'Capacity'])!!}
                    </div>
                    <div class="col-12 col-sm-12 col-md-8">
                        <label for="">Daily target in Ksh:</label>
                        {!! Form::text('daily_target', null, ['class'=>'form-control','placeholder' => 'Ksh...'])!!}
                    </div>
                </div>

            </div>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="info-box">
                <div class="info-box-content">
                    <div class="form-group">
                        {!! Form::submit('Submit', ['class'=>'btn btn-outline-info btn-block']) !!}
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-2"></div>
    </div>
    {{Form::close()}}
@stop

