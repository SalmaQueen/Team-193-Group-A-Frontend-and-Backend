@extends('layouts.admin')
@section('content')
    <h1>Roles</h1>
    <div class="col-sm-6">
        {!! Form::model($role, ['method'=>'PATCH', 'action'=> ['AdminRolesController@update', $role->id]]) !!}
        <div class="form-group">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class'=>'form-control'])!!}
        </div>

        <div class="form-group">
            {!! Form::submit('Update Role', ['class'=>'btn btn-primary col-sm-6 ']) !!}
        </div>
        {!! Form::close() !!}


        {!! Form::open(['method'=>'DELETE', 'action'=> ['AdminRolesController@destroy', $role->id]]) !!}


        <div class="form-group">
            {!! Form::submit('Delete Role', ['class'=>'btn btn-danger col-sm-6']) !!}
        </div>
        {!! Form::close() !!}



    </div>




    <div class="col-sm-6">






    </div>





@stop
