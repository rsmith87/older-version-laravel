@extends('adminlte::page')

@section('content')

<div class="container dashboard tasks tasks-lists-multi col-sm-12 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#task-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Add task list</a>
    <!--<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-briefcase"></i> Assiged tasks</a>-->
  </nav>    
  
				@include('dashboard.includes.alerts')	

       
    <div>
      <div>
        <h1 class="pull-left ml-3 mt-4 mb-2">
          <i class="fas fa-tasks"></i> Task lists
        </h1>
   			<div class="clearfix"></div>
        <p class="ml-3 mb-2">Clients shows all of your client information regarding all cases.  Click on a client to show information.</p>							
		
     </div>
		 @if(count($tasks) > 0)
     <div>
			@if (count($tasks) === 0)
       <div class="alert alert-warning alert-dismissible fade in" role="alert">
        No tasks for this user, yet! <strong>Add a new task above!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
       @endif		       
        @foreach ($tasks as $task)
        <div class="col-md-4 col-xs-12">
          <div class="hidden" id="guid">{{ $task->task_list_uuid }}</div>
              <h3>{{ $task->task_list_name }}</h3> 

          <div class="col-md-6">
              <label>Due</label>
              <p>{{ \Carbon\Carbon::parse($task->due)->format('m/d/Y H:i') }}</p>
          </div>
          <div class="col-md-6">              
              <label>Created</label>
              <p>{{ \Carbon\Carbon::parse($task->created_at)->format('m/d/Y H:i') }}</p>
          </div>
              @if(count($task->Tasks) > 0)
              <ol>
                @foreach($task->Tasks as $t)
                  <li>
                    {{ $t->task_name }}
                  </li>
                @endforeach
               </ol>
              @endif
              <div class="col-xs-12">
              <a class="btn-block btn btn-primary" href="/dashboard/tasks/task/{{ $task->task_list_uuid }}">View task list</a>
              </div>
        </div>
    @endforeach

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