@extends('adminlte::page')

@section('content')

<div class="container dashboard tasks col-sm-12 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#task-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Add task list</a>
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-briefcase"></i> Assiged tasks</a>
  </nav>    
  
				@include('dashboard.includes.alerts')	
			@if (count($tasks) === 0)
       <div class="alert alert-warning alert-dismissible fade in" role="alert">
        No tasks for this user, yet! <strong>Add a new task above!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
       @endif		
       
   <div class="panel panel-primary">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mt-4 mb-2">
          <i class="fas fa-tasks"></i> Task lists
        </h1>
   			<div class="clearfix"></div>
        <p class="ml-3 mb-2">Clients shows all of your client information regarding all cases.  Click on a client to show information.</p>							
		
     </div>
		 @if(count($tasks) > 0)
     <div class="panel-body">
        <table id="main" class="mb-5 table table-responsive table-hover table-{{ $table_color }} table-{{ $table_size }}">
          <thead> 
            <tr>
              <th>Id</th>
              <th>Name</th>
              <th>Due date</th>
            </tr> 
          </thead> 
          <tbody>  
            @foreach ($tasks as $task)
            <tr> 
              <td>{{ $task->id }}</td>
              <td>{{ $task->task_list_name }}</td> 
              <td>{{ \Carbon\Carbon::parse($task->due)->format('m/d/Y H:i') }}</td> 
            </tr> 
                      @endforeach

       

          <table id="main" class="mb-5 table table-responsive table-hover table-{{ $table_color }} table-{{ $table_size }}">
            <thead> 
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Due date</th>
              </tr> 
            </thead> 
            <tbody>  
              @foreach ($tasks as $task)
              <tr> 
                <td>{{ $task->id }}</td>
                <td>{{ $task->task_list_name }}</td> 
                <td>{{ \Carbon\Carbon::parse($task->due)->format('m/d/Y H:i') }}</td> 
              </tr> 
                        @endforeach

            </tbody> 
          </table>            

			@endif
     </div>
  </div>   
  </div>

 

</div>

@include('dashboard.includes.task-modal');


<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
  var tasks = {!! json_encode($tasks->toArray()) !!};  
  var cases = {!! json_encode($cases->toArray()) !!};
  var contacts = {!! json_encode($contacts->toArray()) !!};	
	
  for(var i = 0; i<cases.length; i++){
    cases[i].data = cases[i]['id'];
    cases[i].value = cases[i]['name'];
    delete cases[i]['id'];
    delete cases[i]['name'];
  }
	for(var i = 0; i<contacts.length; i++){
		contacts[i].data = contacts[i]['id'];
		contacts[i].value = contacts[i]['first_name'] + " " + contacts[i]['last_name'];
    delete contacts[i]['last_name'];
    delete contacts[i]['first_name'];
    delete contacts[i]['id'];
	}  
  

  $('input[name="case_name"]').autocomplete({
    lookup: cases,
    width: 'flex',
    triggerSelectOnValidInput: true,
    onSelect: function (suggestion) {
      var thehtml = '<strong>Case '+suggestion.data+':</strong> ' + suggestion.value + ' ';
      var $this = $(this);
      
      $('#outputcontent').html(thehtml);
        $this.prev().val(suggestion.data);
      }

  });
 // console.log(contacts);
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