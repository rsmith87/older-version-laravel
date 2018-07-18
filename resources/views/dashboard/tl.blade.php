@extends('adminlte::page')

@section('content')

<div class="container dashboard task col-sm-12 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#task-modal" href="#"><i class="fas fa-tasks"></i> Edit task list</a>
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#task-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-tasks"></i> Add task</a>
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-tasks"></i> Assiged tasks</a>
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
		
     @if(count($tasks) > 0)
     <div>

 			@if (count($tasks) === 0)
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					No tasks for this task list, yet! <strong>Add a new task above!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				</div>
			@endif	      

          <table class="mb-5 table table-responsive table-{{ $table_color }} table-{{ $table_size }}">
            <thead> 
              <tr class="bg-primary">
                <th>Id</th>
                <th>Name</th>
                <th>Due date</th>
                <th>Complete</th>
              </tr> 
            </thead> 
            <tbody>  
               @foreach ($tasks as $task)
              <tr> 
                <td>{{ $task->id }}</td>
                <td>{{ $task->task_name }}</td> 
                <td>{{ \Carbon\Carbon::parse($task->due)->format('m/d/Y H:i') }}</td> 
                <td><input type="checkbox" name="complete" /></td>
              </tr> 
                        @endforeach

            </tbody> 
          </table>            

			@endif
		
    </div>
  </div>
</div>
@include('dashboard.includes.task-modal');

@foreach($tasks as $task)

  <div class="modal fade" id="task-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h3>
          <i class="fas fa-tasks"></i> Edit task list
          </h3>
          <div class="clearfix"></div>
          <hr />
					
          <form role="form" method="post" action="/dashboard/tasks/add">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $task->id }}" />
						<input type="hidden" name="c" value="{{ $count }}" />

            <div class="col-sm-6 col-12">
              <label for="exampleInputEmail1">Task Name</label>
              <input type="text" class="form-control" value="{{ $task->task_name }}" name="task_name" placeholder="Task Name">
            </div>

            <div class="col-sm-6 col-12">
              <label>Task Description</label>
              <input type="text" class="form-control" value="{{ $task->task_description }}" name="task_description">
            </div>
            
            <div class="col-sm-6 col-12">
              <label>Due date</label>
              <input type="text" class="form-control datepicker" data-toggle="datepicker" id="due_date" value="{{ \Carbon\Carbon::parse($task->due)->format('m/d/Y') }}" name="due_date" aria-label="Due date">
            </div> 

            <div class="col-sm-6 col-12">
              <label>Time start</label>
              <input type="text" class="form-control timepicker-start" id="due_time" value="{{ \Carbon\Carbon::parse($task->due)->format('H:i') }}" name="due_time" aria-label="Time due">
            </div>          
						<div class="clearfix"></div>
            <div class="col-12">
              <label>Case link</label>
              <input type="hidden" name="c_id" value="{{ $task->c_id }}" />
              @if($task->c_id)
                @foreach ($cases as $key => $case)
                  @if($task->c_id == $case->id)
                    <input type="text" name="case_name" value="{{ $case->name }}" class="form-control"  />						 
                  @endif
                @endforeach
              @else
              <input type="text" name="case_name" class="form-control" />		
              @endif
            </div>   

					

            <div class="col-12">
              <label>Contact/Client Link</label>
              <input type="hidden" value="{{ $task->contact_client_id }}" name="contact_id">            

              @if($task->contact_client_id)
                @foreach ($contacts as $key => $contact)
                  @if($task->contact_client_id == $contact->id)              
                    <input type="text" class="form-control" value="{{ $contact->first_name . " " . $contact->last_name }}" name="contact_name">
                  @endif
                @endforeach
              @else
                <input type="text" class="form-control" name="contact_name">
              @endif
            </div>
					
						<div class="col-12">
							<label class="ml-3"><i class="fas fa-tags"></i> Categories</label>   
							<div class="col-sm-12 category-tags">
							 <input class="form-field-hide" name="tags" placeholder='Enter some tags' value="{{ $values }}">
							</div>
						</div>
						
            <div class="col-12">
              <button type="submit" class="btn btn-primary mt-2 mb-5"><i class="fas fa-check"></i> Save</button>
            </div>         

          </form>
					<div class="clearfix"></div>
		@if(isset($task->Subtasks))
					<h3 class="mt-3">
         	  <i class="fas fa-tasks"></i>Subtasks 	
          </h3>
					<hr />
					@endif
					<div class="col-12">
<table class="table table-responsive table-striped table-{{ $table_color }} table-{{ $table_size }}">
									<tbody>									
					@foreach($task->Subtasks as $subtask)
					
					
							@if((int)$task->id === (int)$subtask->t_id)
					
								
										<tr>
											<td></td>
											<td>{{ $subtask->subtask_name }}</td>
											<td>{{  \Carbon\Carbon::parse($subtask->due)->format('m/d/Y H:i') }}</td>
										</tr>

							@endif		
				
					@endforeach			
											</tbody>
								</table>
					</div>
					<div class="col-12">
						<button class="btn-secondary btn mb-4 subtask-show"><i class="fas fa-tasks"></i> Add subtask</button>
					
					
					<div class="col-12 hide">
						<form method="post" action="/dashboard/tasks/subtask/add">
						
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="t_id" value="{{ $task->id }}">
						<input type="hidden" name="c_id" value="{{ $task->c_id }}">
					  <input type="hidden" name="c_c_id" value="{{ $task->contact_client_id }}">
							
            <div class="col-sm-6 col-12">
              <label>Subtask</label>
              <input type="text" class="form-control" name="task_name" placeholder="Task Name">
            </div>
						<div class="col-sm-6 col-12">
							<label>Due date</label>
							<input type="text" class="form-control datepicker" data-toggle="datepicker" id="due_date" name="due_date" aria-label="Due date">
						</div> 							
						<div class="col-sm-6 col-12">
							<label>Time due</label>
							<input type="text" class="form-control timepicker-start" id="due_time" name="due_time" aria-label="Time due">
						</div>    						
						
						<div class="col-12">
							<button class="btn btn-primary" type="submit"><i class="fa fa-plus"></i> Submit</button>
						</form>
					</div>
					</div>
				
						
					
        </div>
      </div>
    </div>
  </div>
</div>

@endforeach



@include('dashboard.includes.task-modal');



<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
  var tasks = {!! json_encode($tasks) !!};  
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