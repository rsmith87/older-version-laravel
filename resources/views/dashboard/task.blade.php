@extends('adminlte::page')

@section('content')

<div class="container dashboard task col-sm-12 col-12 offset-sm-2">
  <nav class="nav nav-pills">
       <a class="nav-item nav-link btn btn-info" href="/dashboard/tasks"><i class="fas fa-arrow-left"></i> Back to tasklists</a>
    
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#task-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-tasks"></i> Add task</a>
    @if(count($tasks) > 0)
      <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#subtask-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-tasks"></i> Add subtask</a>
    @endif
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-tasks"></i> Assiged tasks</a>
  </nav>    
    
	<div class="panel panel-primary">
		<div class="panel-heading" style="overflow:hidden;">
			<h1 class="pull-left ml-3 mt-4 mb-2">
				<i class="fas fa-tasks"></i> Task list: {{ $task_list->task_list_name }} 
			</h1>
			
			<div class="clearfix"></div>
			
			<p class="ml-3 mb-2"></p>			
			
			@include('dashboard.includes.alerts')	
			
						@if (count($tasks) === 0)
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					No tasks for this user, yet! <strong>Add a new task above!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				</div>
			@endif				
			</div>
		
     @if(count($tasks) > 0)
     <div class="panel-body">

       

          <table id="main" class="mb-5 table table-responsive table-{{ $table_color }} table-{{ $table_size }}">
            <thead> 
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Due date</th>
                <th>Complete</th>
              </tr> 
            </thead> 
            <tbody>  
               @foreach ($tasks as $task)
                <tr> 
                  <td class="navigate">{{ $task->id }}</td>
                  <td class='navigate'>{{ $task->task_name }}</td> 
                  <td class='navigate'>{{ \Carbon\Carbon::parse($task->due)->format('m/d/Y H:i') }}</td> 
                  <td></td>
              </tr>
                  @foreach($task->Subtasks as $subtask)
                  
                    @if (\Carbon\Carbon::parse($subtask->complete)->format('Y-m-d H:i:s') != \Carbon\Carbon::parse($zero_datetime)->format('Y-m-d H:i:s'))
                   
             
                    <tr class="table-{{ $table_color === 'light' ? 'dark' : 'light' }}">
                      <td><s>{{ $subtask->id }}</s></td>
                      <td><s>-- {{ $subtask->subtask_name }}</s></td>
                      <td><s>{{ \Carbon\Carbon::parse($subtask->due)->format('m/d/Y H:i') }}</s></td>
                      <td><input type="checkbox" name="complete" checked /></td>
                    </tr>
              
                    @else
                     <tr class="table-{{ $table_color === 'light' ? 'dark' : 'light' }}">
                       <td>{{ $subtask->id }}</td>
                      <td>-- {{ $subtask->subtask_name }}</td>
                      <td>{{ \Carbon\Carbon::parse($subtask->due)->format('m/d/Y H:i') }}</td>
                      <td><input type="checkbox" name="complete" /></td>
                    </tr>       
                     @endif
                  @endforeach                     
               @endforeach

            </tbody> 
          </table>            

			@endif
	</div>
</div>
  
@include('dashboard.includes.task-modal')
  
@include('dashboard.includes.subtask-modal')

@foreach($tasks as $task)
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