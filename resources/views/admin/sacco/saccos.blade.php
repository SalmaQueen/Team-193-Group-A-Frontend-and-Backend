@extends('layouts.admin')
@section('content')
    <h1 class="text-muted text-center">SACCOS</h1>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            @if(Session::has('edited_sacco'))
                <div class="alert alert-success">
                    <ul>
                        <li>{{session('edited_sacco')}}</li>
                    </ul>
                </div>
            @endif
            @if(Session::has('deleted_sacco'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{{session('deleted_sacco')}}</li>
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
            <th>SACCO NAME</th>
            <th>SACCO REG NO.</th>
            <th>ROUTE NAME</th>
            <th>ROUTE NUMBER</th>
            <th>CHAIR NAME</th>
            <th>CHAIR EMAIL</th>
            <th>CHAIR PHONE</th>
            <th>Actions</th>
        </tr>
        @if(isset($saccos))
            @foreach($saccos as $sacco)
                <tr>
                    <td>{{$sacco->sacco_name}}</td>
                    <td>{{$sacco->registration_number}}</td>
                    <td>{{$sacco->route_name}}</td>
                    <td>{{$sacco->route_number}}</td>
                    <td>{{$sacco->chair_name}}</td>
                    <td>{{$sacco->chair_email_address}}</td>
                    <td>{{$sacco->chair_phone_number}}</td>
                    <td>


                        <div class="dropdown">
                            <button class="btn btn-dark btn-block dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu">

                                <li><br><br><a href="{{route('admin.edit', $sacco->id)}}" class="btn btn_opt">Update</a> <br><br></li>

                                {{--Complete user--}}
                                <li>
                                    {!! Form::open(['method'=>'PATCH', 'action'=> ['AdminSaccoController@update', $sacco->id]]) !!}


                                    <input type="hidden" name="is_approved" value="2">


                                    <div class="form-group">
                                        {!! Form::submit('Details', ['class'=>'btn btn_opt','onclick'=>'myFunction()']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </li>

                                {{--Suspend user--}}
                                <li>
                                    {!! Form::open(['method'=>'PATCH', 'action'=> ['AdminSaccoController@update', $sacco->id]]) !!}


                                    <input type="hidden" name="is_approved" value="3">


                                    <div class="form-group">
                                        {!! Form::submit('Notify', ['class'=>'btn btn_opt','onclick'=>'myFunction()']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </li>

                                {{--Activate user--}}
                                <li>
                                    {!! Form::open(['method'=>'PATCH', 'action'=> ['AdminSaccoController@update', $sacco->id]]) !!}


                                    <input type="hidden" name="is_approved" value="1">


                                    <div class="form-group">
                                        {!! Form::submit('Activate', ['class'=>'btn btn_opt','onclick'=>'myFunction()']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </li>

                                {{--Deactivate user--}}
                                <li>
                                    {!! Form::open(['method'=>'PATCH', 'action'=> ['AdminSaccoController@update', $sacco->id]]) !!}


                                    <input type="hidden" name="is_approved" value="0">


                                    <div class="form-group">
                                        {!! Form::submit('Deactivate', ['class'=>'btn btn_opt','onclick'=>'myFunction()']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </li>

{{--                                --}}{{--Delete user--}}
{{--                                <li>--}}
{{--                                    {!! Form::open(['method'=>'DELETE', 'action'=> ['AdminSaccoController@destroy', $sacco->id]]) !!}--}}

{{--                                    <div class="form-group">--}}
{{--                                        {!! Form::submit('Delete', ['class'=>'btn btn_opt']) !!}--}}
{{--                                    </div>--}}

{{--                                    {!! Form::close() !!}--}}
{{--                                </li>--}}
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
            {{$saccos->render()}}
        </div>
        <div class="col-sm-5"></div>
    </div>
@stop
