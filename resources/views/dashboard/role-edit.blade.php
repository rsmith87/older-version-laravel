@extends('adminlte::page')

@section('content')
      
<div class="container dashboard settings firm col-12 col-sm-10 offset-sm-2 scrollspy">
	

	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" href="#general-settings"><i class="fa fa-cog"></i> General settings</a>		
    <a class="nav-item nav-link btn btn-info"  href="#theme-settings"><i class="fas fa-object-group"></i> Theme settings</a>    	
    <a class="nav-item nav-link btn btn-info" href="#table-data-selectors"><i class="fas fa-table"></i> Data table settings</a>
  	<a class="nav-item nav-link btn btn-info" href="#stripe-settings"><i class="fab fa-cc-stripe"></i> Stripe settings</a>
	  <a class="nav-item nav-link btn btn-info" href="/dashboard/settings/users"><i class="fa fa-cog"></i> Users</a>
  </nav>  
 

<div class="col-12" id="firm-info">
	<div class="panel panel-default">
		<div class="panel-heading" style="overflow:hidden;">
			<h1  class="mb-3 mt-4">
				<i class="fas fa-address-card"></i> Create role
			</h1>
			@include('dashboard.includes.alerts')
		</div>
		<div class="panel-body">
			<form method="POST" action="/dashboard/settings/roles/{{$role->id}}/edit">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        {{ Form::label('name', 'Role Name') }}
        {{ Form::text('name', $role->name, array('class' => 'form-control')) }}
    </div>

    <h5><b>Assign Permissions</b></h5>
    @foreach ($permissions as $permission)
		<div class="form-group">
		  @foreach($permissions as $permission)
			<label>{{ ucfirst($permission->name) . " rate" }}</label>
			<input type="checkbox" name="permissions[]"
				   value='{{ $permission->id }}' {{  ? 'checked=checked' : '' }} />
		  @endforeach
        {{Form::checkbox('permissions[]',  $permission->id, $role->permissions ) }}
        {{Form::label($permission->name, ucfirst($permission->name)) }}<br>
		</div>
    @endforeach
    <br>
    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}    	

</div>
    </div>
		</div>
	</div>


	





@endsection
