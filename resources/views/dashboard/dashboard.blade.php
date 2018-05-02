@extends('layouts.dashboard')

@section('content')

<div class="container dashboard home col-sm-10 col-xs-12 offset-sm-2">
  <nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-briefcase"></i> Add case</a>       
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contacts-modal" href="#"><i class="fas fa-address-card"></i> Add contact</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#client-modal" href="#"><i class="fas fa-address-card"></i> Add client</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i class="fas fa-calendar-plus"></i> Add event</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#document-modal" href="#"><i class="fas fa-cloud-upload-alt"></i> Add document</a>
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#task-modal" href="#"><i class="fas fa-tasks"></i> Add Task</a>
	</nav>  	
  	@include('dashboard.includes.alerts')
  
  <div id="dashbaord-main">
  <div class="col-sm-6 col-xs-12 mb-4">
   <div class="panel panel-info">
      <div class="panel-heading">
        <h2 class="pull-left">
          <i class="fas fa-envelope"></i>Messages
        </h2>
     </div>
     <div class="panel-body">
      	@if (count($messages) > 0)

				<table class="table table-responsive table-resposive table-striped table-{{ $table_color }} table-{{ $table_size }}">
					<thead> 
						<tr>           
								<th scope="col">ID</th>
								<th scope="col">Name</th>
						</tr> 
					</thead> 
					<tbody> 
					@foreach($messages as $message)

						<tr>
						  <td>{{ $message[0]['id'] }}</td>
						  <td>{{ $message[0]['body'] }}</td>
						</tr>
					@endforeach
					</tbody> 
				</table>
			@endif	 
     </div>
  </div>   
  </div>

  <div class="col-sm-6 col-xs-12 mb-4">
   <div class="panel panel-success">
      <div class="panel-heading">
        <h2>
          <i class="fas fa-users"></i>Clients
        </h2>
     </div>
     <div class="panel-body">
      	@if (count($clients) > 0)

				<table class="table table-responsive table-resposive table-striped table-{{ $table_color }} table-{{ $table_size }}">
					<thead> 
						<tr>           
								<th scope="col">ID</th>
								<th scope="col">Name</th>
						</tr> 
					</thead> 
					<tbody> 
					@foreach($clients as $client)
						<tr>
						  <td>{{ $client->id }}</td>
						  <td>{{ $client->first_name }}  {{ $client->last_name }}</td>
						</tr>
					@endforeach
					</tbody> 
				</table>
			@endif	
     </div>
  </div>   
  </div>
  
  <div class="col-sm-6 col-xs-12 mb-4">
   <div class="panel panel-warning">
      <div class="panel-heading">
        <h2>
          <i class="fas fa-tasks"></i>Tasks
        </h2>
     </div>
     <div class="panel-body">
 @if (count($tasks) > 0)

				<table class="table table-responsive table-resposive table-striped table-{{ $table_color }} table-{{ $table_size }}">
					<thead> 
						<tr>           
								<th scope="col">ID</th>
								<th scope="col">Name</th>
						</tr> 
					</thead> 
					<tbody> 
					@foreach($tasks as $task)
            @foreach($task->Task as $t)
						<tr>
						  <td>{{ $t->id }}</td>
						  <td>{{ $t->task_name }}</td>
						</tr>
					  @endforeach
          @endforeach
					</tbody> 
				</table>
			@endif	   
     </div>
  </div>   
  </div>
  
  <div class="col-sm-6 col-xs-12 mb-4">
   <div class="panel panel-info">
      <div class="panel-heading">
        <h2>
          <i class="fas fa-cogs"></i> Quick note
        </h2>
     </div>
     <div class="panel-body">
        <form>
          <textarea name="quick_note" class="form-control"></textarea>
          <input type="text" name="relation" class="form-control mt-2 mb-2" />
          <button type="submit" class="btn btn-primary form-control">Submit</button>
       </form>
     </div>
  </div>   
  </div>

</div>
</div>

@include('dashboard.includes.contact-modal')
@include('dashboard.includes.case-modal')
@include('dashboard.includes.client-modal')
@include('dashboard.includes.event-modal')
@include('dashboard.includes.document-modal')
@include('dashboard.includes.task-modal')
<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
var cases = {!! json_encode($cases->toArray()) !!};
var clients = {!! json_encode($clients->toArray()) !!};
var contacts = {!! json_encode($contacts->toArray()) !!};	

  for(var i = 0; i<cases.length; i++){
    //cases[i].data[i].category = 'case'
	  cases[i].id = cases[i]['id'];
	  cases[i].value = cases[i]['name'];
    cases[i].data = 'case';
    
	}

	for(var i = 0; i<clients.length; i++){

		clients[i].id = clients[i]['id'];
		clients[i].value = clients[i]['first_name'] + " " + clients[i]['last_name'];
    clients[i].data = 'client';
	}
	for(var i = 0; i<contacts.length; i++){
    contacts[i].data = 'contact';
		contacts[i].id = contacts[i]['id'];
		contacts[i].value = contacts[i]['first_name'] + " " + contacts[i]['last_name'];
	}	
	arr = cases.concat(clients, contacts);

	 $('input[name="relation"]').autocomplete({
    lookup: arr,
		width: 'flex',
		triggerSelectOnValidInput: true,
    groupBy: 'data',
    onSelect: function (suggestion) {
      var thehtml = '<strong>Case '+suggestion.id+':</strong> ' + suggestion.value + ' ';
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