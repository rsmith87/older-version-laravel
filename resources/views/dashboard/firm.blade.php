@extends('layouts.dashboard')

@section('content')
      
<div class="container dashboard firm col-12 col-sm-10 offset-sm-2 scrollspy">
	

  <nav class="nav nav-pills" id="navbar-interior">
		<a class="nav-item nav-link btn btn-info" href="#firm-info"><i class="fas fa-building"></i> Firm information</a>
    <a class="nav-item nav-link btn btn-info" href="#add-user"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add user</a>
    <a class="nav-item nav-link btn btn-info" href="#current-users"><i class="fas fa-users"></i> Current users</a>
  </nav>  	
 
		@include('dashboard.includes.alerts')

	  <div class="col-12" id="firm-info">
   <div class="panel panel-default">
    <div class="panel-heading" style="overflow:hidden;">
        <h1 style="mb-5" class="pull-left">
         <i class="fas fa-address-card"></i> Firm information
        </h1>
     </div>
     <div class="panel-body">
			<fieldset>
			<form class="form-horizontal" method="post" action="/dashboard/firm/add">
				 <input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="col-sm-4"><!-- Text input-->
			<div class="form-group">
				<label class="col-sm-12 control-label" for="firm_name">Name</label>
				<div class="col-sm-12">
					<input id="firm_name" name="firm_name" type="text" value="{{ $f_name }}" placeholder="Firm Name" class="form-control input-md" required="true">
				</div>
			</div>
			</div>
			<div class="col-sm-4"><!-- Text input-->
			<div class="form-group">
				<label class="col-sm-12 control-label" for="firm_address">Address</label>
				<div class="col-sm-12">
					<input id="firm_address" name="firm_address" type="text" value="{{ $f_address }}" placeholder="Firm Address" class="form-control input-md" required="true">
				</div>
			</div>
			</div>
			<div class="col-sm-4"><!-- Text input-->
			<div class="form-group">
				<label class="col-sm-12 control-label" for="firm_phone">Phone</label>
				<div class="col-sm-12">
					<input id="firm_phone" name="firm_phone" type="text" value="{{ $f_phone }}" placeholder="Phone" class="form-control input-md">
				</div>
			</div>
			</div>
			<div class="col-sm-4 col-"><!-- Text input-->
			<div class="form-group">
				<label class="col-sm-12 control-label" for="firm_fax">Fax Number</label>
				<div class="col-sm-12">
					<input id="firm_fax" name="firm_fax" type="text" value="{{ $f_fax }}" placeholder="Fax" class="form-control input-md">
				</div>
			</div>
			</div>
			<div class="col-sm-4"><!-- Text input-->
			<div class="form-group">
				<label class="col-sm-12 control-label" for="firm_email">Email</label>
				<div class="col-sm-12">
					<input id="firm_email" name="firm_email" type="text" value="{{ $f_email }}" placeholder="Firm Email address" class="form-control input-md" required="true">  </div>
			</div>
			</div>
				</div>
			<div class="col-sm-4 mt-4"><!-- Button -->
			<div class="form-group">
				<div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success">Submit</button>
				</div>
			</div>
			</div>
			</fieldset>
		
			</form>
  		</div>
</div>




<hr />
<div id="add-user" class="col-12">
  <div class="panel panel-default">
    <div class="panel-heading" style="overflow:hidden;">
        <h2 class="pull-left ml-3 mt-3">
         <i class="fas fa-user-plus"></i>Add a user
        </h2>
     </div>

     <div class="panel-body">
			<fieldset>
			<form class="form-horizontal" method="post" action="/dashboard/firm/user/add">
				 <input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="col-sm-12"><!-- Text input-->
			<div class="form-group">
				<label class="col-sm-12 control-label" for="firm_name">Name</label>
				<div class="col-sm-12">
					<input id="new_user_name" name="name" type="text" placeholder="First name" class="form-control input-md" required="true">
				</div>
			</div>
			</div>
				<div class='col-sm-12'>
			<div class="form-group">
				<label class="col-sm-12 control-label" for="firm_name">Email address</label>
				<div class="col-sm-12">
					<input id="new_user_email" name="email" type="text" placeholder="Email address" class="form-control input-md" required="true">
				</div>
			</div>
			</div>	
			<div class="col-sm-12 mt-4"><!-- Button -->
			<div class="form-group">
				<div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success">Submit</button>
				</div>
			</div>
			</div>		
				</form>
			 </fieldset>
</div>
</div>
	</div>
		<hr />
	<div id="current-users" class="col-12">
		
	
		  <div class="panel panel-default">
    <div class="panel-heading" style="overflow:hidden;">
        <h2 class="pull-left ml-3 mt-3">
         <i class="fas fa-user-plus"></i>Current users
        </h2>
     </div>

     <div class="panel-body">
<table class="table table-responsive table-striped table-hover">
          <thead> 
            <tr>
              <th>Id</th>
              <th>Name</th>
							<th>Email</th>
            </tr> 
          </thead> 
          <tbody> 
          @foreach ($firm_staff as $user)
            <tr> 
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td> 
							<td>{{ $user->email }}</td>
            </tr> 
         @endforeach
          </tbody> 
       </table>
			 
				</div>
				
  </div>
  </div>
    </div>

</div>
@foreach ($firm_staff as $user)
 <div class="modal fade" id="user-modal-{{ $user->id }}">
           <div class="modal-dialog">
    <div class="modal-content">
         <div class="modal-body">
           <h3>
             <i class="fas fa-address-card"></i> Edit a user
           </h3>
           <div class="clearfix"></div>
           <hr />
       <form method="post" action="/dashboard/firm/user/add">
         <input type="hidden" name="id" value="{{ $user->id }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

           <div class="col-sm-6 col-xs-12">
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text">Name</span>
               </div>
               <input type="text" class="form-control" value="{{ $user->name }}" name="existing_name" aria-label="Name">
             </div>
           </div>    
           <div class="col-sm-6 col-xs-12">
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text">Email</span>
               </div>
               <input type="text" class="form-control" value="{{ $user->email }}" name="existing_email" aria-label="Email">
             </div>
           </div> 
          
         <button class="btn btn-primary">
           <i class="fas fa-check"></i> Submit
         </button>
				 <button class="btn btn-danger">
					 <i class="fas fa-user-times"></i> Delete
				 </button>
       
                            </form>
       </div>
             </div>
       </div>

</div>
@endforeach
@endsection
