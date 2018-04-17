@extends('layouts.dashboard')

@section('content')
      
<div class="container dashboard settings col-12 col-sm-10 offset-sm-2 scrollspy">
	

  <nav class="nav nav-pills" id="navbar-interior">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#edit-firm-modal" href="#firm-info"><i class="fas fa-building"></i> Firm information</a>
    <a class="nav-item nav-link btn btn-info" href="#add-user"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add user</a>
    <a class="nav-item nav-link btn btn-info" href="#add-client-user"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add client user</a>		
  </nav>  	
 

<div class="col-12" id="firm-info">
	<div class="panel panel-default">
		<div class="panel-heading" style="overflow:hidden;">
			<h1  class="mb-3 mt-4">
			<i class='fa fa-user-plus'></i> Edit {{$user->name}}
			</h1>
			@include('dashboard.includes.alerts')
		</div>
		<div class="panel-body">
		  
    <hr>

			<form method="POST" action="/dashboard/settings/users/edit/{{ $user->id }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="form-group">
						{{ Form::label('name', 'Name') }}
						{{ Form::text('name', $user->name, array('class' => 'form-control')) }}
				</div>

				<div class="form-group">
						{{ Form::label('email', 'Email') }}
						{{ Form::email('email', $user->email, array('class' => 'form-control')) }}
				</div>

    <h5><b>Give Role</b></h5>

    <div class='form-group'>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
    </div>


    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>



@endsection
