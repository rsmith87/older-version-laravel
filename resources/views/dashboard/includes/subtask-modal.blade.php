<div class="modal fade" id="subtask-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h3>
          <i class="fas fa-tasks"></i> Add subtask
        </h3>
        
        <div class="clearfix"></div>
        
        <hr />
        
        <form method="post" action="/dashboard/tasks/subtask/add">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          
         <input type="hidden" name="u_id" value="{{ $user_id }}" />
          
          @if(isset($tl_id))
           <input type="hidden" name="tl_id" value="{{ $tl_id }}">
          @endif
          
          @if(isset($tasks))
           @foreach ($tasks as $task)
            <input type="hidden" name="t_id" value="{{ $task->id }}" />
           @endforeach
          @else
             <input type="hidden" name="t_id" value="{{ $task->id }}" />
         
          @endif
          
          @if(null === Request::segment(5)) 
          
          

          <div class="col-12">
            <label>Name</label>
            <select name="tasks" class="form-control">
              @if(isset($tasks))
                @if(count($tasks) > 0)
                  @foreach($tasks as $task)
                  <option value="{{ $task->id }}">{{ $task->task_name }}</option>
                  @endforeach
                @endif
              @endif
 
            </select>
          </div>
          @endif
          
          <div class="col-sm-6 col-12">
            <label>Name</label>
            <input type="text" class="form-control" name="subtask_name">
          </div>

          <div class="col-sm-6 col-12">
            <label>Description</label>
            <input type="text" class="form-control" name="subtask_description">
          </div>          
          
          <div class="col-sm-6 col-12">
            <label>Due date</label>
            <input type="text" class="form-control dp" data-toggle="dp" id="due_date" name="due_date" aria-label="Due date">
          </div> 
          
          <div class="col-sm-6 col-12">
            <label>Time due</label>
            <input type="text" class="form-control timepicker-start" id="due_time" name="due_time" aria-label="Time due">
          </div>    						

          <div class="col-12">
            <button class="btn btn-primary" type="submit"><i class="fa fa-plus"></i> Save</button>
          </div>       
        </form>
  
      </div>
    </div>
  </div>
</div>