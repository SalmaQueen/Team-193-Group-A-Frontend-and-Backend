@extends('layouts.admin')
@section('content')
    <h1 class="text-muted text-center">ADD A SACCO</h1>
    {!! Form::model($sacco, ['method'=>'PATCH', 'action'=> ['AdminSaccoController@update', $sacco->id],'files'=>true]) !!}
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            @if(Session::has('added_sacco'))
                <div class="alert alert-success">
                    <ul>
                        <li>{{session('added_sacco')}}</li>
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
            @if(Session::has('sacco_exists'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{{session('sacco_exists')}}</li>
                    </ul>
                </div>
            @endif
            @include('includes.form_error')
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
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-plus"></i></span>
                <div class="info-box-content">
                    <div class="form-group">
                        <label for="">Sacco name:</label>
                        {!! Form::hidden('added_by_name')!!}
                        {!! Form::hidden('added_by_email')!!}
                        {!! Form::text('sacco_name', null, ['class'=>'form-control','placeholder' => 'Sacco name'])!!}
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
                    <div class="form-group" style="display: flex;">
                        <div class="col-4">
                            <label for="">Reg. NO:</label>
                            {!! Form::text('registration_number', null, ['class'=>'form-control','placeholder' => 'Registration number'])!!}
                        </div>
                        <div class="col-4">
                            <label for="">Route name:</label>
                            {!! Form::text('route_name', null, ['class'=>'form-control','placeholder' => 'Route name'])!!}
                        </div>
                        <div class="col-4">
                            <label for="">Route number:</label>
                            {!! Form::text('route_number', null, ['class'=>'form-control','placeholder' => 'Route number'])!!}
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
                    <div class="form-group" style="display: flex;">
                        <div class="col-6">
                            <label for="">Chair name:</label>
                            {!! Form::text('chair_name', null, ['class'=>'form-control','placeholder' => 'Chair name'])!!}
                        </div>
                        <div class="col-6">
                            <label for="">Chair ID:</label>
                            {!! Form::number('chair_id_number', null, ['class'=>'form-control','placeholder' => 'Chair ID number'])!!}
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
                <div class="info-box-content" style="display: flex;">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Chair eMail:</label>
                            {!! Form::email('chair_email_address', null, ['class'=>'form-control','placeholder' => 'Chair eMail address'])!!}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Chair phone:</label>
                            {!! Form::text('chair_phone_number', null, ['class'=>'form-control','placeholder' => 'Chair phone number'])!!}
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
                <div class="info-box-content">
                    <div class="form-group">
                        {!! Form::submit('Update', ['class'=>'btn btn-outline-info btn-block','onclick'=>'myFunction()']) !!}
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-2"></div>
    </div>
    {{Form::close()}}

    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            {!! Form::open(['method'=>'DELETE', 'action'=> ['AdminSaccoController@destroy', $sacco->id]]) !!}

            <div class="form-group">
                {!! Form::submit('Delete sacco', ['class'=>'btn btn-outline-danger btn-block','onclick'=>'myFunction()']) !!}
            </div>

            {!! Form::close() !!}
        </div>
        <div class="col-2"></div>
    </div>
@stop


