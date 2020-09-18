@extends("layouts.subscribe")
@section("content")
    <header class="masthead">
        <div class="container">
            {{Form::open(['action'=>'MpesaC2bController@store','method'=>'POST'])}}

            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    @if(Session::has('transaction_accepted'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{session('transaction_accepted')}}</li>
                            </ul>
                        </div>
                    @endif
                    @if(Session::has('transaction_failed'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{session('transaction_failed')}}</li>
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
                    <div class="info-box">
                        <div class="info-box-content">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        {!! Form::hidden('sacco_name')!!}
                                        {!! Form::hidden('CheckoutRequestID')!!}
                                        <label for="">Phone number:</label>
                                        {!! Form::text('PhoneNumber', null, ['class'=>'form-control','placeholder' => 'Enter in format 2547...'])!!}
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
                        <div class="info-box-content">
                            <div class="form-group">
                                <label for="">Select subscription package:</label>
                                {!! Form::select('vehicle_registration_number', [''=>'Select package'] + $vehicles, null, ['class'=>'form-control'])!!}
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
                                {!! Form::submit('Subscribe', ['class'=>'btn btn-outline-info btn-block']) !!}
                            </div>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            {{Form::close()}}
        </div>
    </header>
@stop
