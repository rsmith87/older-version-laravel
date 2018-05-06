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
				 <i class="fa fa-users"></i> User Administration
			</h1>
			@include('dashboard.includes.alerts')
		</div>
		<div class="panel-body">
			 <a href="/dashboard/settings/roles/" class="btn btn-default pull-right">Roles</a>
    <a href="/dashboard/settings/permissions" class="btn btn-default pull-right">Permissions</a>
    <hr>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Operation</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($roles as $role)
                <tr>

                    <td>{{ $role->name }}</td>

                    <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                    <td>
                    <a href="{{ URL::to('dashboard/settings/roles/'.$role->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
										
											<form method="POST" action="/dashboard/settings/roles/destroy/{{ $role->id }}">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
	
                    		{!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    	{!! Form::close() !!}

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <a href="{{ URL::to('dashboard/settings/roles/create') }}" class="btn btn-success">Add Role</a>

</div>
    </div>
		</div>
	</div>
</div>

	





@endsection
