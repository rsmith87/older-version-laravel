@extends('layouts.dashboard')

@section('content')

<div class="container dashboard col-sm-10 col-12 offset-sm-2">
  <nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contacts-modal" href="#"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add contact</a>
		<a class="nav-item nav-link btn btn-info"  href="/dashboard/contacts/mine"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> My contacts</a>
	
  </nav> 
	
	
   <div class="panel panel-default">
    <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mt-4 mb-2">
         <i class="fas fa-address-card"></i> Contacts
        </h1>
				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Contacts shows all of your client information.  Click on a contact to show information.</p>							
						@include('dashboard.includes.alerts')
 					@if (count($contacts) < 1)
           <div class="alert alert-warning alert-dismissible in" role="alert">
						No contacts for this user, yet! <strong>Add a new contact above!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</label>
            </button>
           </div>
		  		  @elseif (count($contacts) >= 1)  
            
     </div>
     <div class="panel-body">
        <table class="table table-responsive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
                    <thead> 
            <tr>           
          @foreach($columns as $column)

              <th scope="col">{{ $column }}</th>
          
          @endforeach
          </tr> 
          </thead>  
          <tbody>
    
            @foreach($contacts as $contact)
						<tr>
              @foreach ($columns as $column)
              <td>{{ $contact->$column }}</td>
              @endforeach
            </tr>
            @endforeach
            
            @endif			
          </tbody> 
       </table>
     </div>
  </div>  


      

    
<div class="modal fade" id="contacts-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">

				<form method="post" action="/dashboard/contacts/add">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="is_client" value="0" />
					<h3>
						<i class="fas fa-address-card"></i> Add a contact
					</h3>
					<div class="clearfix"></div>
					<hr />

					<div class="col-sm-6 col-xs-12">
						<label>First name</label>
						<input type="text" class="form-control" name="first_name" aria-label="First Name">
					</div>  
					
					<div class="col-sm-6 col-xs-12">
						<label>Last name</label>
						<input type="text" class="form-control" name="last_name" aria-label="Last Name">
					</div>   
					
					<div class="col-sm-6 col-xs-12">
						<label>Company</label>
						<input type="text" class="form-control" name="company" aria-label="Company">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Company title</label>
						<input type="text" class="form-control" name="company_title" aria-label="Company title">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Phone</label>
						<input type="text" class="form-control" id="phone" name="phone" aria-label="Phone">
					</div>   
					
					<div class="col-sm-6 col-xs-12">
						<label>Email</label>
						<input type="text" class="form-control" name="email" aria-label="Email">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Address 1</label>
						<input type="text" class="form-control" name="address_1" aria-label="Address">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Address 2</label>
						<input type="text" class="form-control" name="address_2" aria-label="Address">
					</div> 	
					
					<div class="col-sm-6 col-xs-12">
						<label>City</label>
						<input type="text" class="form-control" name="city" aria-label="Address">
					</div> 	 
					
						<div class="col-sm-6 col-xs-12">
						<label>State</label>
						<input type="text" class="form-control" name="state" aria-label="Address">
					</div>
					
					<div class="col-sm-6 col-xs-12">
						<label>Zip</label>
						<input type="text" class="form-control" name="zip" aria-label="Address">
					</div> 	 
					
					<div class="col-sm-6 col-xs-12">
						<label>Case</label>
						<input type="hidden" name="case_id" />							 
						<input type="text" name="case_name" class="form-control" />
					</div>   

					<div class="col-sm-12">
						<button class="btn btn-primary mt-2 mb-1">
							<i class="fas fa-check"></i> Submit
						</button>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>






<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
var cases = {!! json_encode($cases->toArray()) !!};

  for(var i = 0; i<cases.length; i++){
	
	  cases[i].data = cases[i]['id'];
	  cases[i].value = cases[i]['name'];

		//console.log(cases[i])
}
	
	 $('input[name="case_name"]').autocomplete({
    lookup: cases,
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