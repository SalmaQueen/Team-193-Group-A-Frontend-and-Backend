@extends('layouts.admin')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Roles</h1>
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
    <!-- /.content-header -->


    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-4">
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

            {!! Form::open(['method'=>'POST', 'action'=> 'AdminRolesController@store']) !!}
            <div class="form-group">
                {!! Form::label('name', 'Name:') !!}
                {!! Form::text('name', null, ['class'=>'form-control'])!!}
            </div>

            <div class="form-group">
                {!! Form::submit('Create Role', ['class'=>'btn btn-primary','onclick'=>'myFunction()']) !!}
            </div>
            {!! Form::close() !!}



        </div>

    <div class="col-sm-1"></div>


        <div class="col-sm-4">


            @if($roles)


                <table class="table">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th>Created date</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($roles as $role)

                        <tr>
                            <td>{{$role->id}}</td>
                            <td><a href="{{route('roles.edit', openssl_encrypt($role->id,"AES-128-ECB","jghfhskd@#$%%^hflhakdhf3232323232ahkjgf&^^%$&(((^%$$####adskghk8768886djhghkdsjgjkdg"))}}">{{$role->name}}</a></td>
                            <td>{{$role->created_at ? $role->created_at->diffForHumans() : 'no date'}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            @endif



        </div>
<div class="col-sm-1"></div>

    </div>



@stop
