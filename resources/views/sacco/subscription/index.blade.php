@extends('layouts.sacco')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Create a subscriptions</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Logged in as</a></li>
                        <li class="breadcrumb-item active">Sacco</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-4">

            {!! Form::open(['method'=>'POST', 'action'=> 'SaccoSubscriptionsController@store']) !!}
            @if(Session::has('subscription_created'))
                <div class="alert alert-success">
                    <ul>
                        <li>{{session('subscription_created')}}</li>
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
            @if(Session::has('deleted_subscription'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{{session('deleted_subscription')}}</li>
                    </ul>
                </div>
            @endif
            @if(Session::has('updated_subscription'))
                <div class="alert alert-success">
                    <ul>
                        <li>{{session('updated_subscription')}}</li>
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
            <div class="form-group">
                {!! Form::label('amount', 'Amount in Ksh:') !!}
                {!! Form::hidden('sacco_name', null)!!}
                {!! Form::hidden('created_by', null)!!}
                {!! Form::hidden('package', null)!!}
                {!! Form::text('amount', null, ['class'=>'form-control','placeholder'=>'Enter subscription amount'])!!}<br>
                {!! Form::label('period', 'Select period:') !!}
                {!! Form::select('period', array(''=>'Select period of validity:',7 => "1 week", 30=> "1 Month"), null , ['class'=>'form-control'])!!}<br>
                {!! Form::label('number_of_scans', 'Number of scans per day') !!}
                {!! Form::select('number_of_scans', array(''=>'Select number of scans per day:',1 => 1, 2=> 2,3=>3,4=>4,5=>5), null , ['class'=>'form-control'])!!}
            </div>

            <div class="form-group">
                {!! Form::submit('Create subscription', ['class'=>'btn btn-outline-primary btn-block','onclick'=>'myFunction()']) !!}
            </div>
            {!! Form::close() !!}
        </div>

    <div class="col-sm-1"></div>
        <div class="col-sm-4">
            <h5>Subscriptions</h5>
            @if($subscriptions)
                <table class="table">
                    <thead>
                    <tr>
{{--                        <th>id</th>--}}
                        <th>Amount</th>
                        <th>Period</th>
                        <th>Scans</th>
                        <th>Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subscriptions as $subscription)
                        <tr>
{{--                            <td>{{$subscription->id}}</td>--}}
                            <td><a href="{{route('subscriptions.edit', $subscription->id)}}">{{$subscription->amount}}</a></td>
                            <td><a href="{{route('subscriptions.edit', $subscription->id)}}">{{$subscription->period}} days</a></td>
                            <td><a href="{{route('subscriptions.edit', $subscription->id)}}">{{$subscription->number_of_scans}}</a></td>
                            <td>{{$subscription->created_at ? $subscription->created_at->diffForHumans() : 'no date'}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
<div class="col-sm-1"></div>

    </div>



@stop
