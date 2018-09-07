@extends('adminlte::page')

@section('content')

<div class="container dashboard tasks tasks-lists-multi col-sm-12 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#task-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-tasks"></i> Create task board</a>
      @if(Request::segment(3) === 'completed')
          <a class="nav-item nav-link btn btn-info" data-toggle="modal" href="/dashboard/tasklists"><i class="fas fa-tasks"></i> Uncompleted taskboards</a>
      @else
          <a class="nav-item nav-link btn btn-info" data-toggle="modal" href="/dashboard/tasklists/completed"><i class="fas fa-check"></i> <i class="fas fa-tasks"></i> Completed taskboards</a>
      @endif
  </nav>
  
    @include('dashboard.includes.alerts')

       
    <div>
      <div>
        <h1 class="pull-left ml-3 mt-4 mb-2">
          <i class="fas fa-tasks"></i> {{ Request::segment(3) === 'completed' ? 'Completed ' : '' }} Task boards
        </h1>
   			<div class="clearfix"></div>

     </div>
        @if (count($tasks) === 0)
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                No tasks for this user, yet! <strong>Add a new task above!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    @if(count($tasks) > 0)
     <div>

        @foreach ($tasks as $task)
        <div class="col-md-4 col-lg-3 col-sm-5 col-xs-12 tasklist">
          <div class="hidden" id="guid">{{ $task->task_list_uuid }}</div>
            @if($task->complete != null)
                <h4 class="text-green">Completed</h4>
            @endif
            <h4 class="task-title">{{ $task->task_list_name }}</h4>

            @if($task->due)
                <div class="col-xs-6 taskboard-date">
            <label>Due</label>
            <p>{{ \Carbon\Carbon::parse($task->due)->format('m/d/Y g:i A') }}</p>
                </div>
            @endif

            @if($task->created_at)
                <div class="col-xs-6 taskboard-date">
                    <label>Created</label>
                    <p>{{ \Carbon\Carbon::parse($task->created_at)->format('m/d/Y g:i A') }}</p>
                </div>
            @endif

            @if(count($task->Case) > 0)
            <div class="col-xs-12 taskboard-case">
                <label>Case</label>
                <p>{{ $task->Case->name }}</p>
            </div>
            @endif

            <div class="col-xs-12 taskboards-tasks">
            @if(count($task->Task) > 0)
              <label>Tasks:</label>
                <ol>
                @foreach($task->Task as $t)
                    <li>{{ $t->task_name }}</li>
                @endforeach
                </ol>
              @else
                <p><strong>No tasks created for this taskboard</strong></p>
              @endif
            </div>

            <div class="col-xs-12 taskboard-actions">
                <a class="btn btn-block btn-primary" {{ $task->complete != null ? 'style=width:50%;float:left': '' }} href="/dashboard/tasklists/{{ $task->task_list_uuid }}"><i class="fas fa-search"></i> View</a>
                @if($task->complete === null)
                <a class="btn btn-block btn-success" href="/dashboard/tasklists/{{ $task->task_list_uuid }}/complete"><i class="fas fa-check"></i> Complete</a>
                @endif
                <a class="btn btn-block btn-danger" {{ $task->complete != null ? 'style=width:50%;float:left;': '' }} href="/dashboard/tasklists/{{ $task->task_list_uuid }}/delete"><i class="fas fa-trash"></i> Delete</a>
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