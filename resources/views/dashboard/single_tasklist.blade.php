@extends('adminlte::page')

@section('content')


<div class="container dashboard task col-sm-12 col-12 offset-sm-2">
  <nav class="nav nav-pills">
       <a class="nav-item nav-link btn btn-info" href="/dashboard/tasklists"><i class="fas fa-arrow-left"></i> Back to tasklists</a>
  </nav>    
 
  			@include('dashboard.includes.alerts')	
			
				
  
	<div>
		<div>
			<h1 class="pull-left ml-3 mt-4 mb-2">
              <i class="fas fa-tasks"></i> Task list: {{ $task_list->task_list_name }}
			</h1>
			
			<div class="clearfix"></div>
			
			<p class="ml-3 mb-2"></p>			
			
				
			</div>
		
     <div>
  		@if (count($tasks) === 0)
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					No tasks for this tasklist, yet! <strong>Add a new task below!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				</div>
	    @endif
<div class="box box-primary">
            <div class="box-header ui-sortable-handle" style="cursor: move;">


			  <div class="col-sm-6 col-xs-12">
			  <h5>Description</h5>
			  <p>{{ $task_list->task_list_description }}</p>
			  </div>
			  <div class="col-sm-6 col-xs-12">
				<h5>Due date</h5>
				<p>{{ $task_list->due }}</p>
			  </div>

			  <div class="col-xs-12">
				<h5>Tags</h5>
			  <select class="js-category-tasklist" name="categories[]" multiple="multiple">
				@foreach($tags as $c)
				  <option value="name" selected="selected">{{ $c[0]->name }}</option>
				@endforeach
			  </select>
			  </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list ui-sortable" data-widget="todo-list">
           @foreach ($tasks as $task)
            @if($task->complete != null)
                <li class="strikethrough">
            @else
                <li>
            @endif
                  <!-- drag handle -->
                  <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <!-- checkbox -->
                  @if($task->complete != null)
                  <input type="checkbox" class="task-checkbox" checked="checked" name="task_complete_{{ $task->id }}" />
                  @else
                  <input type="checkbox" class="task-checkbox" name="task_complete_{{ $task->id }}" />
                  @endif
                  <!-- todo text -->
                  <span class="text">{{ $task->task_name }}</span>
                  <!-- Emphasis label -->
                  @if(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now())                  
                  <small class="label label-danger"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHour())
                  <small class="label label-warning"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHours(4))
                  <small class="label label-info"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHours(8))
                  <small class="label label-success"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHours(16))
                  <small class="label label-primary"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($task->due) < \Carbon\Carbon::now()->addHours(24))
                  <small class="label label-default"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}</small>
                  @endif
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <i class="fa fa-edit" data-toggle="modal" data-target="#task-modal-{{ $task->task_uuid }}"></i>
                    <i class="fa fa-trash" data-toggle="modal" data-target="#delete-task-modal-{{ $task->task_uuid }}"></i>
                  </div>
                </li>
                @foreach($task->Subtasks as $subtask)                 
                
                <li>
<!-- drag handle -->
                  <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <!-- checkbox -->
                  <input type="checkbox" class="subtask-checkbox" name="subtask_complete_{{ $subtask->id }}" value="">
                  <!-- todo text -->
                  <span class="text">-->{{ $subtask->subtask_name }}</span>
                  <!-- Emphasis label -->
                  @if(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now())                  
                  <small class="label label-danger"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHour())
                  <small class="label label-warning"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(4))
                  <small class="label label-info"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(8))
                  <small class="label label-success"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(16))
                  <small class="label label-primary"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(24))
                  <small class="label label-default"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @endif
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <span>Delete from the task modal</span>
                  </div>
                </li>
                @endforeach
              @endforeach
          </ul>           
              
              </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#task-modal"><i class="fa fa-plus"></i> Add task</button>
            </div>
          </div>
	</div>
</div>    
 

@include('dashboard.includes.task-modal')
  
@foreach($tasks as $task)
<div class="modal fade" id="delete-task-modal-{{ $task->task_uuid }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h3>
            <i class="fas fa-tasks"></i> Delete task
          </h3>
          <hr />
          <div class="clearfix"></div>
          <p>Really delete task: {{ $task->task_name }} ? </p>
          <form method="POST" action="/dashboard/tasks/delete-task" />
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <input type="hidden" name="task_uuid" value="{{ $task->task_uuid }}" />
          <input type="submit" class="btn btn-block btn-danger" value="Delete task">
          </form>
        </div>
      </div>
    </div>
</div>

 <div class="modal fade" id="task-modal-{{ $task->task_uuid }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h3>
            <i class="fas fa-tasks"></i> Edit task
          </h3>
          <hr />
          <div class="clearfix"></div>
          <label>Name</label>
          <input type="text" name="task_name" class="form-control" value="{{ $task->task_name }}" />
          <div class="col-sm-6 col-xs-12">
          <label>Due date</label>
          <input type="text" name="due_date" class="form-control datepicker" value="{{ \Carbon\Carbon::parse($task->due)->format('m/d/Y') }}" />
          </div>
          <div class="col-sm-6 col-xs-12">
          <label>Due time</label>
          <input type="text" name="due_time" class="form-control timepicker-start" value="{{ \Carbon\Carbon::parse($task->due)->format('H:i') }}" />      
          </div>
          <label>Subtasks</label>
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
                  <input type="checkbox" class="subtask-checkbox" name="subtask_complete_{{ $subtask->id }}" value="">
                  <!-- todo text -->
                  <span class="text">{{ $subtask->subtask_name }}</span>
                  <!-- Emphasis label -->
                  @if(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now())                  
                  <small class="label label-danger"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHour())
                  <small class="label label-warning"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(4))
                  <small class="label label-info"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(8))
                  <small class="label label-success"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(16))
                  <small class="label label-primary"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @elseif(\Carbon\Carbon::parse($subtask->due) < \Carbon\Carbon::now()->addHours(24))
                  <small class="label label-default"><i class="fa fa-clock"></i>{{ \Carbon\Carbon::parse($subtask->due)->diffForHumans() }}</small>
                  @endif
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <form method="POST" action="/dashboard/tasks/subtask/delete">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                      <input type="hidden" name="st_id" value="{{ $subtask->id }}" />
                      <button class="trash-button" type="submit"><i class="fa fa-trash"></i></button>
                    </form>
                  </div>
                </li>


                    
          @endforeach
          </ul>
             <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right add-subtask-button"><i class="fa fa-plus"></i> Add subtask</button>
            </div>             
              <div class="subtask-add hide">
                <form method="POST" action="/dashboard/tasks/subtask/add">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                  <input type="hidden" name="task_id" value="{{ $task->id }}" />
                  <input type="hidden" name="task_list_uuid" value="{{ $task->task_list_uuid }}" />
                  <div class="col-xs-12">
                    <label>Subtask</label>
                    <input type="text" class="form-control" name="subtask_name">
                  </div>

                  <div class="col-sm-6 col-12">
                    <label>Due date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="datetimepicker" name="due_date" aria-label="Due date">
                    </div>
                  </div>

                  <div class="col-sm-6 col-12">
                    <label>Time due</label>
                    <input type="text" class="form-control timepicker-start" id="timepicker" name="due_time" aria-label="Time due">
                  </div>    						

                  <div class="col-12">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-plus"></i> Save</button>
                  </div> 
               </form>
                
              </div>
         
              </div>
            
          
          <button type="submit" class="btn btn-primary form-control">Submit</button>
         </div>
      </div>
    </div>
  </div>          


   

@endforeach
<script type="text/javascript">
  var categories = {!! json_encode($tags) !!}
  console.log(categories);
</script>
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
	
	
  $('.add-subtask-button').click(function(){
    var $this = $(this);
    $subtask_hide = $this.parent().parent().find('.subtask-add').removeClass('hide');
  });

  $('.task-checkbox').on('click', function(){
    var $this = $(this);
    var $name = $this.attr('name');
    console.log($name);
    $name = $name.replace('task_complete_', '');
    $name = $name.replace('sub', '');
    console.log($name);
   $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });   
      $.ajax({
       type: 'POST',
       contentType: "application/json; charset=utf-8",
       
       url: '/dashboard/tasks/task/'+$name+'/complete',
       success:function(data){
        console.log('success');
        console.log(data);
      },
     })
  });
  
 $('.subtask-checkbox').on('click', function(){
    var $this = $(this);
    var $name = $this.attr('name');
    //console.log($name);
    $name = $name.replace('task_complete_', '');
    $name = $name.replace('sub', '');
    console.log($name);
   $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });   
      $.ajax({
       type: 'POST',
       contentType: "application/json; charset=utf-8",
       
       url: '/dashboard/tasks/task/subtask/'+$name+'/complete',
       success:function(data){

      },
     })
  }); 

</script>
@endsection