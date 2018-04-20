@extends('layouts.dashboard')

@section('content')

<div class="container dashboard case col-sm-10 col-12 offset-sm-2">
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#case-edit-modal" href="#"><i class="fas fa-briefcase"></i> Edit case</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target='#add-hours-modal' href='#'><i class="fas fa-plus"></i> <i class="fas fa-clock"></i> Add hours</a>
		@if(!empty($case->Contacts))		
		@foreach($case->Contacts as $contact)
			@if($contact->is_client))		
				<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#payment-modal-full" href="#"><i class="fas fa-dollar-sign"></i> Bill {{ $contact->first_name }} {{ $contact->last_name }}</a>
		  @endif
		@endforeach
		@endif
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i class="fas fa-calendar-plus"></i> Add event</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#document-modal" href="#"><i class="fas fa-cloud-upload-alt"></i> Add document</a>  		
		@if(count($order) > 0)
		<a class="nav-item nav-link btn btn-info" href="/dashboard/invoices"><i class="fa fa-file"></i> View invoices</a>
		@endif
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
					<label>Total cost</label>
					@if($case->billing_type === 'hourly')
					<p>${{ number_format($case->hours * $case->billing_rate, 2) }}</p>
					@else
					<p>${{ number_format($case->billing_rate, 2) }}</p>
					@endif
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

			
					@if(count($case->Documents) > 0)
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
						@foreach($case->Documents as $document)							
							<tr>
								<td>{{ $document->name }}</td>
								<td>{{ $document->description }}</td>
								<td><button class="btn btn-primary btn-sm download" href="{{ $document->path }}">Download</button></td>
							</tr>
						@endforeach	
						</tbody>
					</table>           
					@endif
				
				
				
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

@include('dashboard.includes.event-modal')
@include('dashboard.includes.document-modal');
@include('dashboard.includes.invoice-modal')
@include('dashboard.includes.case-modal')

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
           <div class="col-sm-6 col-xs-12 mt-4">
            
           
                  <label>Statute of Limitations</label>
          	
                <input type="checkbox" {{ !empty($case->statute_of_limitations) ? "checked" : '' }} name="statute_of_limitations" aria-label="Statute of Limitations">
   
    
           </div>
           <div class="col-sm-6 col-xs-12">
             
          
            <label>Rate</label>
          <input type="text" class="form-control" name="billing_rate" value="{{ $case->billing_rate }}" aria-label="Amount (to the nearest dollar)">

           </div>
					 
           <div class="col-sm-6 col-xs-12 mt-4">
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
					             
                 <button class="btn btn-primary mt-3"><i class="fas fa-check"></i> Submit</button>             
        
					 
					 </div>
          
           <div class="clearfix"></div>
					 </div>
			</div>
		 </div>
	</div>

<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
var cases = {!! json_encode($cases->toArray()) !!};
var clients = {!! json_encode($clients->toArray()) !!};
var contacts = {!! json_encode($contacts->toArray()) !!};	

  for(var i = 0; i<cases.length; i++){
	  cases[i].data = cases[i]['id'];
	  cases[i].value = cases[i]['name'];
	}
	for(var i = 0; i<clients.length; i++){
		clients[i].data = clients[i]['id'];
		clients[i].value = clients[i]['first_name'] + " " + clients[i]['last_name'];
	}
	for(var i = 0; i<contacts.length; i++){
		contacts[i].data = contacts[i]['id'];
		contacts[i].value = contacts[i]['first_name'] + " " + contacts[i]['last_name'];
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
	$('input[name="client_name"]').autocomplete({
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
	$('input[name="contact_name"]').autocomplete({
    lookup: contacts,
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