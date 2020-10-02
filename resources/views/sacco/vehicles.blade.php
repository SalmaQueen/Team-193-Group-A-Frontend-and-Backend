@extends('layouts.sacco')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <h1 class="text-muted text-center">VEHICLES</h1>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    @if(Session::has('edited_vehicle'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{session('edited_vehicle')}}</li>
                            </ul>
                        </div>
                    @endif
                    @if(Session::has('deleted_vehicle'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{session('deleted_vehicle')}}</li>
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
                    <div id="pay">
                        <script>
                            function myFunction() {
                                document.getElementById("pay").innerHTML =
                                    '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                    '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                    '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                    '<p class="text-success">Loading..</p>'+
                                    '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                    '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                    '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n';
                            };
                        </script>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <table class="table table-hover table-secondary">
                <tr>
                    <th>REGISTRATION NO.</th>
                    <th>NICKNAME</th>
                    <th>DRIVER NAME</th>
                    <th>DRIVER'S PHONE</th>
                    <th>CONDUCTOR'S NAME</th>
                    <th>CONDUCTOR'S PHONE</th>
                    <th>CAPACITY</th>
                    <th>DAILY TARGET</th>
        {{--            <th>PROGRESS</th>--}}
                    <th>Actions</th>
                </tr>
                @if(isset($vehicles))
                    @foreach($vehicles as $vehicle)
                        <tr>
                            <td>{{$vehicle->vehicle_registration_number}}</td>
                            <td>{{$vehicle->vehicle_nickname}}</td>
                            <td>{{$vehicle->driver_name}}</td>
                            <td>{{$vehicle->drivers_phone_number}}</td>
                            <td>{{$vehicle->conductor_name}}</td>
                            <td>{{$vehicle->conductors_phone_number}}</td>
        {{--                    <td>{{$vehicle->capacity}} seater</td>--}}
                            <td>{{$vehicle->capacity}} seater</td>
                            <td>{{$vehicle->daily_target}}</td>
        {{--                    <td>VehicleProgress</td>--}}
                            <td>


                                <div class="dropdown">
                                    <button class="btn btn-dark btn-block dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
                                    <ul class="dropdown-menu">

                                        <li><br><br><a href="{{route('sacco.edit', $vehicle->id)}}" class="btn btn_opt">Update</a> <br><br></li>

                                        <li>
                                            {!! Form::open(['method'=>'PATCH', 'action'=> ['SaccoVehicleActionsController@update', $vehicle->id]]) !!}


                                            <input type="hidden" name="is_active" value="4">


                                            <div class="form-group">
                                                {!! Form::submit('Warn', ['class'=>'btn btn_opt','onclick'=>'myFunction()']) !!}
                                            </div>
                                            {!! Form::close() !!}
                                        </li>
                                        <li><a href="{{route('sacco.edit', $vehicle->id)}}" class="btn btn_opt">Notify</a> <br><br></li>

                                        {{--Suspend user--}}
        {{--                                <li>--}}
        {{--                                    {!! Form::open(['method'=>'PATCH', 'action'=> ['SaccoVehicleActionsController@update', $vehicle->id]]) !!}--}}


        {{--                                    <input type="hidden" name="is_active" value="3">--}}


        {{--                                    <div class="form-group">--}}
        {{--                                        {!! Form::submit('Notify', ['class'=>'btn btn_opt']) !!}--}}
        {{--                                    </div>--}}
        {{--                                    {!! Form::close() !!}--}}
        {{--                                </li>--}}

                                        <li>
                                            {!! Form::open(['method'=>'PATCH', 'action'=> ['SaccoVehicleActionsController@update', $vehicle->id]]) !!}


                                            <input type="hidden" name="is_active" value="1">


                                            <div class="form-group">
                                                {!! Form::submit('Activate', ['class'=>'btn btn_opt','onclick'=>'myFunction()']) !!}
                                            </div>
                                            {!! Form::close() !!}
                                        </li>

                                        <li>
                                            {!! Form::open(['method'=>'PATCH', 'action'=> ['SaccoVehicleActionsController@update', $vehicle->id]]) !!}


                                            <input type="hidden" name="is_active" value="0">


                                            <div class="form-group">
                                                {!! Form::submit('Deactivate', ['class'=>'btn btn_opt','onclick'=>'myFunction()']) !!}
                                            </div>
                                            {!! Form::close() !!}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
            <div class="row">
                <div class="col-sm-5"></div>
                <div class="col-sm-2 col-sm-offset-1">
                    {{$vehicles->render()}}
                </div>
                <div class="col-sm-5"></div>
            </div>
        </div>
    </section>
@stop
