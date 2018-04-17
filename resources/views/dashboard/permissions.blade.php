@extends('layouts.dashboard')

@section('content')
      
<div class="container dashboard settings col-12 col-sm-10 offset-sm-2 scrollspy">
	


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
				<i class="fa fa-users"></i> Permissions
			</h1>
			@include('dashboard.includes.alerts')
		</div>
		<div class="panel-body">
		 <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Permissions</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td> 
                    <td>
                    <a href="{{ URL::to('/dashboard/settings/permissions/'.$permission->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    {!! Form::open(['method' => 'POST', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ URL::to('dashboard/settings/permissions/create') }}" class="btn btn-success">Add Permission</a>

        </table>
    </div>
		</div>
	</div>
</div>

	

<hr />





@endsection
