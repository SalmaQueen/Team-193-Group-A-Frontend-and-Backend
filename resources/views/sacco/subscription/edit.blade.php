@extends('layouts.sacco')
@section('content')
    <div class="row">
        <div class="col-sm-3">

        </div>
        <div class="col-sm-6">
            <h1 class="text-muted">Edit subscription</h1><hr><hr>
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
            {!! Form::model($subscription, ['method'=>'PATCH', 'action'=> ['SaccoSubscriptionsController@update', $subscription->id]]) !!}
            <div class="form-group">
                {!! Form::label('amount', 'Amount in Ksh:') !!}
                {!! Form::text('amount', null, ['class'=>'form-control','placeholder'=>'Enter subscription amount'])!!}<br>
                {!! Form::label('period', 'Select period:') !!}
                {!! Form::select('period', array(''=>'Select period of validity:',7 => "1 week", 30=> "1 Month"), null , ['class'=>'form-control'])!!}<br>
                {!! Form::label('number_of_scans', 'Number of scans per day') !!}
                {!! Form::select('number_of_scans', array(''=>'Select number of scans per day:',1 => 1, 2=> 2,3=>3,4=>4,5=>5), null , ['class'=>'form-control'])!!}
                <br>
                <div class="form-group">
                    {!! Form::submit('Update subscription', ['class'=>'btn btn-outline-primary btn-block','onclick'=>'myFunction()']) !!}
                </div>
            </div>
            {!! Form::close() !!}

            {!! Form::open(['method'=>'DELETE', 'action'=> ['SaccoSubscriptionsController@destroy', $subscription->id]]) !!}

            <div class="form-group">
                {!! Form::submit('Delete subscription', ['class'=>'btn btn-outline-danger btn-block','onclick'=>'myFunction()']) !!}
            </div>

            {!! Form::close() !!}
        </div>
        <div class="col-sm-3">
        </div>
    </div>
@stop
