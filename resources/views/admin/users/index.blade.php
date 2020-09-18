@extends('layouts.admin')
@section('content')
    @if(Session::has('deleted_user'))
        <div class="alert alert-danger">
            <strong>Success!</strong> {{session('deleted_user')}}
        </div>
    @endif

    @if(Session::has('edited_user'))
        <p class="bg-danger"></p>
        <div class="alert alert-success">
            <strong>Success!</strong> {{session('edited_user')}}
        </div>
    @endif

    @if(Session::has('completed_user'))
        <p class="bg-danger"></p>
        <div class="alert alert-info">
            <strong>Success!</strong> {{session('completed_user')}}
        </div>
    @endif

    @if(Session::has('activated_user'))
        <p class="bg-danger"></p>
        <div class="alert alert-success">
            <strong>Success!</strong> {{session('activated_user')}}
        </div>
    @endif

    @if(Session::has('deactivated_user'))
        <p class="bg-danger"></p>
        <div class="alert alert-danger">
            <strong>Success!</strong> {{session('deactivated_user')}}
        </div>
    @endif

    @if(Session::has('suspended_user'))
        <p class="bg-danger"></p>
        <div class="alert alert-warning">
            <strong>Success!</strong> {{session('suspended_user')}}
        </div>
    @endif

    @if(Session::has('created_user'))
        <div class="alert alert-success">
            <strong>Success!</strong> {{session('created_user')}}
        </div>
    @endif


    <h1>Users</h1>

    <table class="table">
       <thead>
         <tr>
             <th>Name</th>
             <th>Email</th>
             <th>Role</th>
             <th>Registered</th>
             <th>Updated</th>
             <th>Status</th>
             <th>Actions</th>
          </tr>
        </thead>
        <tbody>

        @if($users)


            @foreach($users as $user)


           <tr>
              <td>{{$user->name}}</td>
              <td>{{$user->email}}</td>
              <td>{{$user->role ? $user->role->name : 'User has no role'}}</td>
               <td>{{$user->created_at->diffForHumans()}}</td>
               <td>{{$user->updated_at->diffForHumans()}}</td>
               <td>
                   @if($user->is_active == 1)
                       <p class="btn btn-success btn-block">Active</p>
                   @elseif($user->is_active == 2)
                       <p class="btn btn-info btn-block">Completed</p>
                   @elseif($user->is_active == 3)
                       <p class="btn btn-warning btn-block">Suspended</p>
                   @else
                       <p class="btn btn-danger btn-block">Inactive</p>
                   @endif
               </td>
               <td>


                   <div class="dropdown">
                       <button class="btn btn-dark btn-block dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
                       <ul class="dropdown-menu">

                           <li><br><br><a href="{{route('users.edit', openssl_encrypt($user->id,"AES-128-ECB","jghfhskd@#$%%^hflhakdhf3232323232ahkjgf&^^%$&(((^%$$####adskghk8768886djhghkdsjgjkdg"))}}" class="btn btn_opt">Update</a> <br><br></li>

                            {{--Complete user--}}
                           <li>
                               {!! Form::open(['method'=>'PATCH', 'action'=> ['AdminActionsController@update', $user->id]]) !!}


                               <input type="hidden" name="is_approved" value="2">


                               <div class="form-group">
                                   {!! Form::submit('Complete', ['class'=>'btn btn_opt']) !!}
                               </div>
                               {!! Form::close() !!}
                           </li>

                           {{--Suspend user--}}
                           <li>
                               {!! Form::open(['method'=>'PATCH', 'action'=> ['AdminActionsController@update', $user->id]]) !!}


                               <input type="hidden" name="is_approved" value="3">


                               <div class="form-group">
                                   {!! Form::submit('Suspend', ['class'=>'btn btn_opt']) !!}
                               </div>
                               {!! Form::close() !!}
                           </li>

                           {{--Activate user--}}
                           <li>
                               {!! Form::open(['method'=>'PATCH', 'action'=> ['AdminActionsController@update', $user->id]]) !!}


                               <input type="hidden" name="is_approved" value="1">


                               <div class="form-group">
                                   {!! Form::submit('Activate', ['class'=>'btn btn_opt']) !!}
                               </div>
                               {!! Form::close() !!}
                           </li>

                           {{--Deactivate user--}}
                           <li>
                               {!! Form::open(['method'=>'PATCH', 'action'=> ['AdminActionsController@update', $user->id]]) !!}


                               <input type="hidden" name="is_approved" value="0">


                               <div class="form-group">
                                   {!! Form::submit('Deactivate', ['class'=>'btn btn_opt']) !!}
                               </div>
                               {!! Form::close() !!}
                           </li>

                           {{--Delete user--}}
                           <li>
                               {!! Form::open(['method'=>'DELETE', 'action'=> ['AdminUsersController@destroy', $user->id]]) !!}

                                   <div class="form-group">
                                       {!! Form::submit('Delete', ['class'=>'btn btn_opt']) !!}
                                   </div>

                               {!! Form::close() !!}
                           </li>
                       </ul>
                   </div>
               </td>
           </tr>

            @endforeach


          @endif


       </tbody>
     </table>

    <div class="row">
        <div class="col-sm-5"></div>
        <div class="col-sm-2 col-sm-offset-1">
            {{$users->render()}}
        </div>
        <div class="col-sm-5"></div>
    </div>


@stop
