@extends('layouts.dashboard')

@section('content')

<div class="container dashboard document col-sm-10 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#document-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Edit document</a>
		
	</nav>  	

	@include('dashboard.includes.alerts')
	
   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-4 mt-4 mb-5"> 
         <i class="fas fa-cloud-upload-alt"></i> Document
        </h1>
     </div>
     <div class="panel-body">
			 <div class="container">
			
				 <div class="col-sm-6 col-12">
			 <label>Name</label>
			 <p>{{ $document->name }}</p>
			 <label>Description</label>
			 <p>{{ $document->description }}</p>


				 </div>
			<div class="col-sm-6 col-12">					 
			 <label>Links</label>
					 
				@if(empty($document->case_id))

					 <p>No case </p>
				@else			
					@foreach ($cases as $case)
						@if($document->case_id == $case->id)
 
				<p>{{ $case->name }}</p>						 
					 @endif
					@endforeach
				@endif
								

				@if(empty($document->client_id)) 
			 <p>No client </p>
				@else
					@foreach ($clients as $client)
						@if($document->client_id == $client->id)				 
					 <p>{{ $client->first_name . " " . $client->last_name }}</p>
						@endif
					@endforeach	
				@endif
					 
			 @if(empty($document->contact_id))
			 <p>No client</p>
				@else
					@foreach ($contacts as $contact)
						@if($document->contact_id == $contact->id)				 
					    <p>{{ $contact->first_name . " " . $contact->last_name }}</p>
					  @endif
				 	@endforeach				 
				@endif
				 </div>
<div class="col-12">

	<iframe src="https://s3.amazonaws.com/legaleeze{{ $document->path }}" style="width:100%;height:300px;"></iframe>
				 </div>   
         
			 </div>
     </div>
  </div>   

	

<div class="modal fade" id="document-modal">
           <div class="modal-dialog modal-lg">
    <div class="modal-content">
         <div class="modal-body">
           <h3>
             <i class="fas fa-cloud-upload-alt"></i> Edit document
           </h3>
           <div class="clearfix"></div>
           <hr />
         <form method="post" action="/dashboard/documents/upload" enctype="multipart/form-data">
				 <input type="hidden" name="id" value="{{ $document->id }}" />
					 <input type="hidden" name="file_path" value="{{ $document->path }}" />
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
       
       <div class="col-sm-6 col-12">
         <label for="file_name">File Name</label>
         <input type="text" class="form-control" value="{{ $document->name }}" name="file_name" />
       </div>
       <div class="col-sm-6 col-12">
         <label for="file_description">File Description</label>
         <input type="text" class="form-control" name="file_description" value="{{ $document->description }}" placeholder="File Description" />
       </div>
					 <div class="clearfix"></div>
					 <hr />
			@if(!$user->hasRole('client'))		 
       <div class="col-sm-6 col-12">
				 <label for="case_name">Case link</label>
				@if(empty($document->case_id))
				 		<input type="hidden" name="case_id"  /> 
					  <input type="text" name="case_name" class="form-control" placeholder="Case name" />
				@else			
					@foreach ($cases as $case)
						@if($document->case_id == $case->id)
							<input type="hidden" name="case_id" value="{{ $case->id }}" /> 
							<input type="text" name="case_name" class="form-control" value="{{ $case->name }}" />						 
					 @endif
					@endforeach
				@endif
					 </div>
       <div class="col-sm-6 col-12">
					 
				 <label for="client_name">Client link</label>
				 
				@if(empty($document->client_id)) 
				 	 <input type="hidden" name="client_id" />
					 <input type="text" name="client_name" placeholder="Client name" class="form-control" />
				@else
					@foreach ($clients as $client)
						@if($document->client_id == $client->id)				 
						 <input type="hidden" name="client_id" value="{{ $client->id }}" />
						 <input type="text" name="client_name" value="{{ $client->first_name . " " . $client->last_name }}" class="form-control" />
						@endif
					@endforeach	
				@endif
					 </div>
					        <div class="col-sm-6 col-12">

					 <label for="contact_name">Contact link</label>
			 
				@if(empty($document->contact_id))
				   <input type="hidden" name="contact_id" />
					 <input type="text" name="contact_name" class="form-control" placeholder="Contact Name" /> 
				@else
					@foreach ($contacts as $contact)
						@if($document->contact_id == $contact->id)				 
						 <input type="hidden" name="contact_id" value="{{ $contact->id }}" />
						 <input type="text" name="contact_name" value="{{ $contact->first_name . " " . $contact->last_name }}" class="form-control" />
					 @endif
					@endforeach
										
				@endif
				@endif
					 </div>    
			<div class="col-12">
         <button class="btn btn-primary mt-3 mb-3" type="submit">
           <i class="fas fa-check"></i> Submit
         </button>
       </div>
					 
			 </div>
					

					 </form>
       </div>
             </div>
       </div>
      
     </div>  
    
	


<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
CKEDITOR.replace('ckeditor_one');
</script>
<script src="{{ asset('js/autocomplete.js') }}"></script>
@if($user->hasRole('administrator') || $user->hasRole('authenticated_user'))
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
@endif       



@endsection