@extends('layouts.dashboard')

@section('content')
      
<div class="container dashboard firm col-12 col-sm-10 offset-sm-2 scrollspy">
	

  <nav class="nav nav-pills" id="navbar-interior">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#edit-firm-modal" href="#firm-info"><i class="fas fa-building"></i> Firm information</a>
    <a class="nav-item nav-link btn btn-info" href="#add-user"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add user</a>
    <a class="nav-item nav-link btn btn-info" href="#add-client-user"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add client user</a>		
  </nav>  	
 

<div class="col-12" id="firm-info">
	<div class="panel panel-default">
		<div class="panel-heading" style="overflow:hidden;">
			<h1  class="mb-3 mt-4">
				<i class="fas fa-address-card"></i> Firm information
			</h1>
			@include('dashboard.includes.alerts')
		</div>
		<div class="panel-body">
			<div class="col-sm-6 col-12">
				<label>Name</label>
				<p>{{ $firm['name'] }}</p>
				<label>Phone</label>
				<p>{{ $firm['phone'] }}</p>
				<label>Fax</label>
				<p>{{ $firm['fax'] }}</p>
				<label>Email</label>
				<p>{{ $firm['email'] }}</p>	
			</div>
			<div class="col-sm-6 col-12">
				<label>Address</label>
				<p>{{ $firm['address_1'] }}<br />
					{{ isset($firm['address_2']) ? $firm['address_2'] : "" }}<br />
					{{ $firm['city'] }}, {{ $firm['state'] }} {{ $firm['zip'] }}</p>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="edit-firm-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
					<i class="fas fa-building"></i> Edit firm information
				</h3>
				<div class="clearfix"></div>
				<hr />	
			<form class="form-horizontal" method="post" action="/dashboard/firm/add">
				 <input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="col-sm-6"><!-- Text input-->
				<label class=" control-label" for="firm_name">Name</label>

					<input id="firm_name" name="name" type="text" value="{{ $firm['name'] }}" placeholder="Firm Name" class="form-control input-md" required="true">
		
			</div>

			<div class="col-sm-6"><!-- Text input-->

				<label for="firm_phone">Phone</label>
					<input id="firm_phone" name="phone" type="text" value="{{ $firm['phone'] }}" placeholder="Phone" class="form-control input-md">
			</div>
			<div class="col-sm-6"><!-- Text input-->
				<label for="firm_fax">Fax Number</label>
					<input id="firm_fax" name="fax" type="text" value="{{ $firm['fax'] }}" placeholder="Fax" class="form-control input-md">
			
			</div>
			<div class="col-sm-6"><!-- Text input-->
				<label for="firm_email">Email</label>
					<input id="firm_email" name="email" type="text" value="{{ $firm['email'] }}" placeholder="Firm Email address" class="form-control input-md" required="true">
			</div>	
			<div class="col-sm-6"><!-- Text input-->
				<label>Address 1</label>
				<input id="firm_address" name="address_1" type="text" value="{{ $firm['address_1'] }}" placeholder="Address Line 1" class="form-control input-md" required="true">
			</div>					
			<div class="col-sm-6"><!-- Text input-->
				<label  for="firm_address">Address 2</label>
					<input id="firm_address" name="address_2" type="text" value="{{ $firm['address_2'] }}" placeholder="Address Line 2" class="form-control input-md">
				
			
			</div>				
			<div class="col-sm-6"><!-- Text input-->
				<label  for="firm_address">City</label>
					<input id="firm_address" name="city" type="text" value="{{ $firm['city'] }}" placeholder="City" class="form-control input-md" required="true">
			</div>	
			<div class="col-sm-6"><!-- Text input-->
				<label  for="firm_address">State</label>
					<input id="firm_address" name="state" type="text" value="{{ $firm['state'] }}" placeholder="State" class="form-control input-md" required="true">
			</div>	
			<div class="col-sm-6"><!-- Text input-->
				<label  for="firm_address">Zip</label>
					<input id="firm_address" name="zip" type="text" value="{{ $firm['zip'] }}" placeholder="Zip" class="form-control input-md" required="true">
			</div>					
		
			
			<div class="col-12" id="firm-submit"><!-- Button -->
					<button id="submit" name="submit" class="btn btn-primary btn-lg mt-3">Submit</button>
			
			</div>
		
			</form>
</div>
		</div>
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
				<label  for="firm_name">Name</label>
				<div class="col-sm-12">
					<input id="new_user_name" name="name" type="text" placeholder="First name" class="form-control input-md" required="true">
				</div>
			</div>
			</div>
				<div class='col-sm-12'>
			<div class="form-group">
				<label  for="firm_name">Email address</label>
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

	@if (count($firm_staff) > 0)

				<table class="table table-responsive table-resposive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
					<thead> 
						<tr>           
								<th scope="col">ID</th>
								<th scope="col">Name</th>
								<th scope="col">Email</th>
						</tr> 
					</thead> 
					<tbody> 
					@foreach($firm_staff as $user)
					
						<tr>
						  <td>{{ $user['id'] }}</td>
						  <td>{{ $user['name'] }}</td>
						  <td>{{ $user['email'] }}</td>
						</tr>
					@endforeach
					</tbody> 
				</table>
			@endif	 
</div>
</div>
	</div>
	
	<hr />
	
<div id="add-client-user" class="col-12">
  <div class="panel panel-default">
    <div class="panel-heading" style="overflow:hidden;">
        <h2 class="pull-left ml-3 mt-3">
         <i class="fas fa-user-plus"></i>Give a client login access
        </h2>
     </div>

     <div class="panel-body">
			<fieldset>
			<form class="form-horizontal" method="post" action="/dashboard/firm/user/client/add">
				 <input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="col-sm-12"><!-- Text input-->
			<div class="form-group">
				<label  for="firm_name">Name</label>
				<div class="col-sm-12">
					 <input type="hidden" name="client_id" />
					 <input type="text" name="name" class="form-control" id="client_name" placeholder="Client name" />										
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
			 
			 @if (count($clients) > 0)
				<table class="table table-responsive table-resposive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
					<thead> 
						<tr>           
								<th scope="col">ID</th>
								<th scope="col">Name</th>
								<th scope="col">Email</th>
						</tr> 
					</thead> 
					<tbody> 
					@foreach($clients as $user)
						@if($user->has_login != 0)
						<tr>
						  <td>{{ $user->id }}</td>
						  <td>{{ $user->first_name }} {{ $user->last_name }}</td>
						  <td>{{ $user->email }}</td>
						</tr>
						@endif
					@endforeach
					</tbody> 
				</table>
			@endif	 
</div>
</div>
	</div>

    </div>

</div>


<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
var clients = {!! json_encode($clients->toArray()) !!};
for(var i = 0; i<clients.length; i++){
		clients[i].data = clients[i]['id'];
		clients[i].value = clients[i]['first_name'] + " " + clients[i]['last_name'];
}
	
	$('input[id="client_name"]').autocomplete({
    lookup: clients,
		width: 'flex',
		triggerSelectOnValidInput: true,
    onSelect: function (suggestion) {
      var thehtml = '<strong>Case '+suggestion.data+':</strong> ' + suggestion.value + ' ';
			//alert(thehtml);
			var $this = $(this);
      $('#outputcontent').html(thehtml);
   		$this.prev().val(suggestion.data);
			
    }
		 
  });
</script>
@endsection
