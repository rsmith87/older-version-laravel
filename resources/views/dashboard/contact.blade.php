@extends('layouts.dashboard')

@section('content')

<div class="container dashboard contact col-sm-10 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contact-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Edit client</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contacts-modal" href="#"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add client</a>		
 		@if($contact->is_client === 1 )
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contact-relate-case-modal" href="#"><i class="fa fa-user"></i> <i class="fa fa-plus"></i> <i class="fa fa-briefcase"></i> Relate client to case</a>		
 		@else
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contact-relate-case-modal" href="#"><i class="fa fa-user"></i> <i class="fa fa-plus"></i> <i class="fa fa-briefcase"></i> Relate contact to case</a>				
		@endif
	</nav>  	

	
   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-4 mb-2"> 
          <i class="fas fa-briefcase"></i> {{ ucfirst(Request::segment(3)) }} 
        </h1>
				@include('dashboard.includes.alerts')
				
     </div>
     <div class="panel-body">
			 <div class="container">
			
				 <div class="col-sm-6 col-12">
			 <label>Name</label>
			 <p>{{ $contact->first_name }} {{ $contact->last_name }}</p>
			 <label>Address</label>
			 <p>{{ $contact->address }}</p>
			 <label>Company</label>
			 <p>{{ $contact->company }}</p>

				 </div>
				 <div class="col-sm-6 col-12">					 
			 <label>Company title</label>
			 <p>{{ $contact->company_title }}</p>
					 <label>Phone</label>
					 <p>{{ $contact->phone }}</p>
					 <label>Email</label>
					 <p>{{ $contact->email }}</p>
				 </div>

          <div class="clearfix"></div>
				 @if(count($contact->Documentsclients) > 0)
           <h3 class="mt-5 mb-2">
             <i class="fas fa-user"></i>Documents
           </h3>
           
           <table class="table table-responsive table-striped table-{{ $table_color }}">
          <thead>
            <tr> 
              <th>File name</th> 
              <th>File description</th>
              <th>Download link</th>

            </tr> 
          </thead> 
          <tbody> 
						@if($contact->is_client === 1)
            	@foreach($contact->Documentsclients as $document)
								<tr>
									<td>{{ $document->name }}</td>
									<td>{{ $document->description }}</td>
									<td><button class="btn btn-primary btn-sm" href="{{ $document->location }}">Download</button></td>
								</tr>
            	@endforeach						
						@else
						  @foreach($contact->Documents as $document)
								<tr>
									<td>{{ $document->name }}</td>
									<td>{{ $document->description }}</td>
									<td><button class="btn btn-primary btn-sm" href="{{ $document->location }}">Download</button></td>
								</tr>
            	@endforeach							
						@endif
            
          </tbody>
          </table>           
           <hr />
				@endif 

				@if(count($contact->Tasks) > 0)
				 <div class="clearfix"></div>
           <h3 class="mt-5 mb-2">
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
				  	@foreach($contact->Tasks as $task)
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

	

<div class="modal fade" id="contact-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
				<i class="fas fa-address-card"></i> Add a contact
				</h3>
				<div class="clearfix"></div>
				<hr />
					<form method="post" action="/dashboard/contacts/add">
						<input type="hidden" name="id" value="{{ $contact->id }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="is_client" value="{{ $is_client }}" />


						<div class="col-sm-6 col-xs-12">
							<label>First name</label>
							<input type="text" class="form-control" value="{{ $contact->first_name }}" name="first_name" aria-label="First Name">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Last name</label>
							<input type="text" class="form-control" value="{{ $contact->last_name }}" name="last_name" aria-label="Last Name">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Company</label>
							<input type="text" class="form-control"  value="{{ $contact->company }}" name="company" aria-label="Company">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Company title</label>
							<input type="text" class="form-control" value="{{ $contact->company_title }}" name="company_title" aria-label="Company title">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Phone</label>
							<input type="text" class="form-control" id="phone" value="{{ $contact->phone }}" name="phone" aria-label="Phone">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Email</label>
							<input type="text" class="form-control" value="{{ $contact->email }}" name="email" aria-label="Email">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Address</label>
							<input type="text" class="form-control" value="{{ $contact->address }}" name="address" aria-label="Address">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Case</label>
							<input type="hidden" name="case_id" value="{{ !empty($contact->case_id) ? $contact->case_id : '' }}" />		
							<input type="text" name="case_name" value="{{ $contact->case_id != 0 || !empty($array_cases[$contact->case_id]) ? $array_cases[$contact->case_id] : '' }}" class="form-control" />
						</div>
						
						<div class="col-sm-12">
							<button class="btn btn-primary mt-1 mb-5">
								<i class="fas fa-check"></i> Submit
							</button
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
	
	$('input[name="case_name"]').on('keyup', function(){
		var $this = $(this);
		if($this.text() == ""){
			$this.prev().attr('value', 0);
		}
		else
			{
				$this.prev().attr('value')
			}
	});
	
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