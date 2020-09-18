@extends('layouts.admin')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Logged in as</a></li>
                        <li class="breadcrumb-item active">Admin</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="row">
        @include('includes.form_error')
    </div>

    <div class="row">
        <div class="col-sm-2">
{{--            <img style="margin-left: 20px; width: 250px;" height="250" src="{{$user->photo ? $user->photo->file : 'http://placehold.it/400x400'}}" alt="" class="img-responsive img-rounded">--}}


        </div>



        <div class="col-sm-8">


            {!! Form::model($user, ['method'=>'PATCH', 'action'=> ['AdminUsersController@update', $user->id],'files'=>true]) !!}


            <div class="form-group">
                {!! Form::label('name', 'Name:') !!}
                {!! Form::text('name', null, ['class'=>'form-control'])!!}
            </div>


            <div class="form-group">
                {!! Form::label('email', 'Email:') !!}
                {!! Form::email('email', null, ['class'=>'form-control'])!!}
            </div>

            <div class="form-group">
                {!! Form::label('admission_number', 'Admission number:') !!}
                {!! Form::text('admission_number', null, ['class'=>'form-control'])!!}
            </div>

            <div class="form-group">
                {!! Form::label('role_id', 'Role:') !!}
                {!! Form::select('role_id',  $roles , null, ['class'=>'form-control'])!!}
            </div>


            <div class="form-group">
                {!! Form::label('is_active', 'Status:') !!}
                {!! Form::select('is_active', array(1 => 'Active', 0=> 'Not Active'), null , ['class'=>'form-control'])!!}
            </div>





            <div class="form-group">
                {!! Form::label('password', 'Password:') !!}
                {!! Form::password('password', ['class'=>'form-control'])!!}
            </div>





            <div class="form-group">
                {!! Form::submit('Update User', ['class'=>'btn btn-primary col-sm-6']) !!}
            </div>

            {!! Form::close() !!}






             {!! Form::open(['method'=>'DELETE', 'action'=> ['AdminUsersController@destroy', $user->id]]) !!}



                 <div class="form-group">
                    {!! Form::submit('Delete user', ['class'=>'btn btn-danger col-sm-6']) !!}
                 </div>

               {!! Form::close() !!}




        </div>

        <div class="col-sm-2"></div>


    </div>









@stop
