@extends('layouts.dashboard')

@section('content')

<div class="container dashboard case col-sm-10 col-12 offset-sm-2">
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Add case</a>
		<a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#case-edit-modal" href="#"><i class="fas fa-briefcase"></i> Edit Cases</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target='#add-hours-modal' href='#'><i class="fas fa-clock"></i> Add hours</a>		
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#payment-modal-full" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Bill client</a>
	</nav>  	

	<div class="panel panel-default">
		<div class="panel-heading" style="overflow:hidden;">
			<h1 class="pull-left ml-3 mt-4 mb-2">
				<i class="fas fa-briefcase"></i> Case
			</h1>
			<div class="clearfix"></div>
			<p class="ml-3 mb-4">
				Case information for {{ $case->name }}
			</p>							
			@include('dashboard.includes.alerts')
		</div>
		<div class="panel-body">
			<div class="col-sm-6 col-12">
				<label>Status</label>
				<p>{{ ucfirst($case->status) }}</p>
				<label>Name</label>
				<p>{{ $case->name }}</p>
				<label>Case number</label>
				<p>{{ $case->number }}</p>
				<label>Case description</label>
				<p>{{ $case->description }}</p>
				<label>Court name</label>
				<p>{{ $case->court_name }}</p>
				<label>Opposing Councel</label>
				<p>{{ $case->opposing_councel }}</p>
				<label>Location</label>
				<p>{{ $case->location }}</p>
			</div>
				<div class="col-sm-6 col-12">					 
					<label>Claim reference number</label>
					<p>{{ $case->claim_reference_number }}</p>
					<label>Statute of Limitations</label>
					<p>{{ $case->statute_of_limitations }}</p>
					<label>Open date</label>
					<p>{{ \Carbon\Carbon::parse($case->open_date)->format('m/d/Y') }}</p>
					<label>Close date</label>
					<p>{{ \Carbon\Carbon::parse($case->close_date)->format('m/d/Y') }}</p>
					<label>Rate</label>
					<p>${{ $case->billing_rate }}</p>
					<label>Hours</label>
					<p>{{ $case->hours }} hours</p>
					<label>Rate type</label>
					<p>{{ ucfirst($case->billing_type) }}</p>
				</div>

				<div class="clearfix"></div>
				
				@if(!empty($case->Contacts))
					@foreach($case->Contacts as $contact)
						@if($contact->is_client === 1)
							<h3 class="mt-5 mb-3">
								<i class="fas fa-user"></i>Client
							</h3>
							<table class="table table-responsive table-striped table-{{ $table_color }} mb-3">
								<thead>
									<tr> 
										<th>Id</th>
										<th>Name</th> 
										<th>Phone Number</th>
										<th>Email</th>

									</tr> 
								</thead> 
								<tbody> 
									<tr>
										<td>ID</td>
										<td>{{ $contact->first_name }} {{ $contact->last_name }}</td>
										<td>{{ $contact->phone }}</td>
										<td>{{ $contact->email }}</td>
									</tr>
								</tbody>
							</table>	
						@endif
					@endforeach
				@endif

				@if(!empty($case->Contacts))
					@foreach($case->Contacts as $contact)
						@if($contact->is_client != 1)	
							<div class="clearfix"></div>
							<h3 class="mt-3 mb-3">
							<i class="fas fa-user"></i>Contacts
							</h3>
							<table class="table table-responsive table-striped table-{{ $table_color }} mb-3">
								<thead>
									<tr> 
										<th>Id</th>
										<th>Name</th> 
										<th>Phone Number</th>
										<th>Email</th>
										<th>Relationship</th>										
									</tr> 
								</thead> 
								<tbody> 
									<tr>
										<td>ID</td>
										<td>{{ $contact->first_name }} {{ $contact->last_name }}</td>
										<td>{{ $contact->phone }}</td>
										<td>{{ $contact->email }}</td>
									</tr>
								</tbody>
							</table>
						@endif
					@endforeach
				@endif

				@foreach($case->Documents as $document)
					@if(!empty($document))
					<div class="clearfix"></div>
					<h3 class="mt-3 mb-3">
						<i class="fas fa-user"></i>Documents
					</h3>
					<table class="table table-responsive table-striped table-{{ $table_color }} mb-3">
						<thead>
							<tr> 
								<th>File name</th> 
								<th>File description</th>
								<th>Download link</th>
							</tr> 
						</thead> 
						<tbody> 
							<tr>
								<td>{{ $document->name }}</td>
								<td>{{ $document->description }}</td>
								<td><button class="btn btn-primary btn-sm" href="{{ $document->location }}">Download</button></td>
							</tr>
						</tbody>
					</table>           
					@endif
				@endforeach
				
				
				@if(count($case->Tasks) > 0)
           <h3 class="mt-3 mb-3">
             <i class="fas fa-user"></i>Tasks
           </h3>
        
           <table class="table table-responsive table-striped table-{{ $table_color }}">
          <thead>
            <tr> 
              <th>Name</th> 
              <th>Description</th>
            </tr> 
          </thead> 
          <tbody> 
				  	@foreach($case->Tasks as $task)
				 			<tr>
								<td>{{ $task->task_name }}</td>
								<td>{{ $task->task_description }}</td>
						</tr>
				 		@endforeach
						 </tbody>
				 </table>
				 @endif
		</div>
	</div>
</div>


<div class="modal fade" id="payment-modal-full">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
					<i class="fas fa-tasks"></i> Create invoice
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form role="form" method="post" action="/dashboard/invoices/invoice/create">
					<input type="hidden" name="case_id" value="{{ $case->id }}"/>
					<input type="hidden" name="invoicable_id" value="{{ !empty($invoicable_id) ? $invoicable_id : "" }}" />
					<input type="hidden" name="total_amount" value="{{ $invoice_amount }}.00" />
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-12">
						<label>Amount</label>
						<input type="text" class="form-control" value="{{ $invoice_amount }}.00" name="amount" />
					</div>
					<div class="col-12 mb-2">
						<label class="mt-2">Client</label>
						@if(!empty($clients))
							@foreach($clients as $client)
								<input type="hidden" name="client_id" value="{{ $client->id }}" />
								<input type="text" class="form-control" class="mb-3" name="contact_name" value="{{ $client->first_name }} {{ $client->last_name }}" />
							@endforeach
						@else
							<input type="hidden" name="client_id" />
							<p>Enter a contact name to start creating one now!</p>
							<input type="text" class="form-control" class="mb-3" name="contact_name" />	
						@endif
					</div>
					<div class="col-12">
						<input type="submit" class="btn btn-primary mt-2 form-control" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add-hours-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
					<i class="fas fa-tasks"></i> Add hours to case
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form role="form" method="post" action="/dashboard/cases/case/add-hours">
					<input type="hidden" name="case_id" value="{{ $case->id }}"/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-12">
						<label>Amount</label>
						<input type="text" class="form-control" name="hours_worked" />
					</div>
					<div class="col-12">
						<input type="submit" class="btn btn-primary mt-2 form-control" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="case-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
					<i class="fas fa-briefcase"></i>Add new case
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form role="form" method="post" action="/dashboard/cases/create">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-sm-6 col-xs-12">
						<label>Status</label>
						<select class="custom-select" id="inputGroupSelect01" name="status" aria-label="Status" aria-describedby="inputGroup-sizing-sm">
							<option value="" selected>Choose...</option>
							<option value="active">Active</option>
							<option value="inactive">Inactive</option>
						</select>
					</div>       

					<div class="col-sm-6 col-xs-12">
						<label>Case Number</label>
						<input type="text" class="form-control" id="case_number" name="case_number" aria-label="Case Number">
					</div>
					<div class="col-sm-12 col-xs-12">
						<label>Name</label>
						<input type="text" class="form-control" id="name" name="name" aria-label="Name">
					</div>   
					<div class="col-sm-12">
						<label>Description</label>
						<textarea class="form-control" aria-label="Description" name="description" id="description"></textarea>
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Court name</label>
						<input type="text" class="form-control" id="court_name" name="court_name" aria-label="Court name">
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Opposing Councel</label>
						<input type="text" class="form-control" id="opposing_councel" name="opposing_councel" aria-label="Opposing Councel">
					</div>              
					<div class="col-sm-6 col-xs-12">
						<label>Location</label>
						<input type="text" class="form-control" id="location" name="location" aria-label="Location">
					</div>   
					<div class="col-sm-6 col-xs-12">
						<label>Claim Reference Number</label>
						<input type="text" class="form-control" id="claim_reference_number" name="claim_reference_number" aria-label="Claim reference number">
					</div>   
					<div class="col-sm-6 col-xs-12">
						<label>Open date</label>
						<input type="text" class="form-control datepicker" data-toggle="datepicker" id="open_date" name="open_date" aria-label="Open date">
					</div>  
					<div class="col-sm-6 col-xs-12">
						<label>Close date</label>
						<input type="text" class="form-control datepicker" data-toggle="datepicker" id="close_date" name="close_date" aria-label="Close date">
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Statute of Limitations</span>
						<input type="checkbox" name="statute_of_limitations" aria-label="Statute of Limitations">
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Rate</label>
						<input type="text" class="form-control" name="billing_rate" aria-label="Amount (to the nearest dollar)">
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Hours</label>
						<input type="text" class="form-control" name="hours" aria-label="Hours worked">
					</div>					 
					<div class="col-sm-6 col-xs-12">
						<label>Fixed rate</label>
						<input type="radio" name="rate_type" value="fixed" aria-label="Fixed rate">
						<label>Hourly rate</label>
						<input type="radio" name="rate_type" value="hourly" aria-label="Hourly rate">
					</div>
					<div class="col-12">
						<button class="btn btn-primary mt-3"><i class="fas fa-check"></i> Submit</button>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="case-edit-modal">
	 <div class="modal-dialog modal-lg">
    <div class="modal-content">
         <div class="modal-body">
           <h3>
             <i class="fas fa-briefcase"></i> Edit case
           </h3>
           <div class="clearfix"></div>
           <hr />
         <form role="form" method="post" action="/dashboard/cases/create">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id" value="{{ $case->id }}">
          <div class="col-sm-6 col-xs-12">
            
 
       
              <label for="inputGroupSelect01">Status</label>
         
            <select class="custom-select" name="status" id="inputGroupSelect01" aria-label="Status" aria-describedby="inputGroup-sizing-sm">
			        @foreach($status_values as $t)
         	      <option value="{{ $t }}" {{ $t == $case->status ? "selected='selected'" : '' }}>{{ ucwords($t) }}</option>
			        @endforeach 
            </select>
         
          </div>       
           
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Case Number</label>
               <input type="text" class="form-control" id="case_number" value="{{ $case->number }}" name="case_number" aria-label="Case Number">
           </div>
           <div class="col-sm-12 col-xs-12">
             
               
                 <label>Name</label>
               <input type="text" class="form-control" id="name" value="{{ $case->name }}" name="name" aria-label="Name">
           </div>   
           <div class="col-sm-12">
             
               
                 <label>Description</label>
               <textarea class="form-control" aria-label="Description" name="description" id="description">{{ $case->description }}</textarea>
           </div>
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Court name</label>
               <input type="text" class="form-control" id="court_name" value="{{ $case->court_name }}" name="court_name" aria-label="Court name">
           </div>
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Opposing Councel</label>
             
               <input type="text" class="form-control" id="opposing_councel" value="{{ $case->opposing_councel }}" name="opposing_councel" aria-label="Opposing Councel">
           </div>              
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Location</label>
               <input type="text" class="form-control" id="location" value="{{ $case->location }}" name="location" aria-label="Location">
           </div>   
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Claim Reference Number</label>
               <input type="text" class="form-control" id="claim_reference_number" value="{{ $case->claim_reference_number }}" name="claim_reference_number" aria-label="Claim reference number">
           </div>   
            <div class="col-sm-6 col-xs-12">
             
               
                 <label>Open date</label>
               <input type="text" class="form-control datepicker" value="{{ \Carbon\Carbon::parse($case->open_date)->format('m/d/Y') }}" id="open_date" name="open_date" aria-label="Open date">
           </div>  
            <div class="col-sm-6 col-xs-12">
             
               
                 <label>Close date</label>
               <input type="text" class="form-control datepicker" id="close_date" value="{{ \Carbon\Carbon::parse($case->close_date)->format('m/d/Y') }}"
  name="close_date" aria-label="Close date">
           </div>
           <div class="col-sm-6 col-xs-12">
            
           
                  <label>Statute of Limitations</label>
          	
                <input type="checkbox" {{ !empty($case->statute_of_limitations) ? "checked" : '' }} name="statute_of_limitations" aria-label="Statute of Limitations">
   
    
           </div>
           <div class="col-sm-6 col-xs-12">
             
          
            <label>Rate</label>
          <input type="text" class="form-control" name="billing_rate" value="{{ $case->billing_rate }}" aria-label="Amount (to the nearest dollar)">

           </div>
           <div class="col-sm-6 col-xs-12">
             @php
						 $types = ['fixed', 'hourly'];
						 @endphp
            @foreach($types as $type)
						 	<label>{{ ucfirst($type) . " rate" }}</label>
							<input type="radio" name="rate_type" value='{{ $type }}' {{ $case->billing_type === $type ? 'checked=checked' : '' }}" />
						@endforeach


           
            </div>
					 
					 					<div class="col-sm-6 col-xs-12">
						<label>Hours</label>
						<input type="text" class="form-control" name="hours" value="{{ $case->hours }}" aria-label="Hours worked">
					</div>	
					 <div class="col-sm-12 col-xs-12">
					             
                 <button class="btn btn-primary"><i class="fas fa-check"></i> Submit</button>             
        
					 
					 </div>
          
           <div class="clearfix"></div>
					 </div>
			</div>
		 </div>
	</div>

<script src="{{ asset('js/autocomplete.js') }}"></script>			
<script type="text/javascript">
 var contacts = {!! json_encode($clients->toArray()) !!};	
	
	for(var i = 0; i<contacts.length; i++){
		contacts[i].data = contacts[i]['id'];
		contacts[i].value = contacts[i]['first_name'] + " " + contacts[i]['last_name'];
    delete contacts[i]['last_name'];
    delete contacts[i]['first_name'];
    delete contacts[i]['id'];
	}  
  

  $('input[name="contact_name"]').autocomplete({
    lookup: contacts,
    width: 'flex',
    triggerSelectOnValidInput: true,
    onSelect: function (suggestion) {
      var thehtml = '<strong>Case '+suggestion.data+':</strong> ' + suggestion.value + ' ';
      var $this = $(this);
      
      $('#outputcontent').html(thehtml);
        $this.prev().val(suggestion.data);
      }

  });
	
</script>


@endsection