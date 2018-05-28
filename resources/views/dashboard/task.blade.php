@extends('adminlte::page')

@section('content')

<div class="container dashboard task col-sm-12 col-12 offset-sm-2">
  <nav class="nav nav-pills">
       <a class="nav-item nav-link btn btn-info" href="/dashboard/tasks"><i class="fas fa-arrow-left"></i> Back to tasklists</a>
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
				<i class="fas fa-tasks"></i> Task list
			</h1>
			
			<div class="clearfix"></div>
			
			<p class="ml-3 mb-2"></p>			
			
				
			</div>
		
     @if(count($tasks) > 0)
     <div class="panel-body">
<div class="box box-primary">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">{{ $task_list->task_list_name }}</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list ui-sortable">
           @foreach ($tasks as $task)
                <li>
                  <!-- drag handle -->
                  <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <!-- checkbox -->
                  <input type="checkbox" value="">
                  <!-- todo text -->
                  <span class="text">{{ $task->task_name }}</span>
                  <!-- Emphasis label -->
                  @if(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now())                  
                  <small class="label label-danger"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHour())
                  <small class="label label-warning"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHours(4))
                  <small class="label label-info"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHours(8))
                  <small class="label label-success"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHours(16))
                  <small class="label label-primary"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHours(24))
                  <small class="label label-default"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @endif
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                  </div>
                </li>

                    
@endforeach
          </ul>           
               

			@endif
              </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#task-modal"><i class="fa fa-plus"></i> Add item</button>
            </div>
          </div>
	</div>
</div>    
 

@include('dashboard.includes.task-modal')
  
@include('dashboard.includes.subtask-modal')

@foreach($tasks as $task)
 <div class="modal fade" id="task-modal-{{ $task->id }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h3>
            <i class="fas fa-tasks"></i> Edit subtask
          </h3>

          <div class="clearfix"></div>

          <hr />
          <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list ui-sortable">
           @foreach ($task->Subtasks as $subtask)
                <li>
                  <!-- drag handle -->
                  <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <!-- checkbox -->
                  <input type="checkbox" value="">
                  <!-- todo text -->
                  <span class="text">{{ $subtask->subtask_name }}</span>
                  <!-- Emphasis label -->
                  @if(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now())                  
                  <small class="label label-danger"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHour())
                  <small class="label label-warning"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(4))
                  <small class="label label-info"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(8))
                  <small class="label label-success"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(16))
                  <small class="label label-primary"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(24))
                  <small class="label label-default"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @endif
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                  </div>
                </li>

                    
          @endforeach
          </ul>           
               

		
              </div>
         </div>
      </div>
    </div>
  </div>          


   

@foreach($task->Subtasks as $subtask)
  <div class="modal fade" id="subtask-modal-{{ $subtask->id }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h3>
            <i class="fas fa-tasks"></i> Edit subtask
          </h3>

          <div class="clearfix"></div>

          <hr />

          <form method="post" action="/dashboard/tasks/subtask/add">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $subtask->id }}">
            <input type="hidden" name="t_id" value="{{ $task->id }}">
            <input type="hidden" name="tl_id" value="{{ $tl_id }}">
            <input type="hidden" name="u_id" value="{{ $user_id }}" />

            <div class="col-sm-6 col-12">
              <label>Subtask</label>
              <input type="text" class="form-control" name="subtask_name" value="{{ $subtask->subtask_name }}">
            </div>

            <div class="col-sm-6 col-12">
              <label>Due date</label>
              <input type="text" class="form-control datepicker" data-toggle="datepicker" value="{{ \Carbon\Carbon::parse($subtask->due)->format('m/d/Y') }}" id="due_date" name="due_date" aria-label="Due date">
            </div> 

            <div class="col-sm-6 col-12">
              <label>Time due</label>
              <input type="text" class="form-control timepicker-start" id="due_time" value="{{ \Carbon\Carbon::parse($subtask->due)->format('H:i') }}" name="due_time" aria-label="Time due">
            </div>    						

            <div class="col-12">
              <button class="btn btn-primary" type="submit"><i class="fa fa-plus"></i> Save</button>
            </div>       
          </form>

        </div>
      </div>
    </div>
  </div>
  @endforeach
@endforeach

<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
  @if(count($tasks) > 0)
  var tasks = {!! json_encode($task->toArray()) !!};  
  @endif
  var cases = {!! json_encode($cases->toArray()) !!};
  var contacts = {!! json_encode($contacts->toArray()) !!};	
	var tags = {!! json_encode($tags) !!};
	
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