<div class="modal fade" id="task-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h3>
          <i class="fas fa-tasks"></i> Add new task
        </h3>
        <div class="clearfix"></div>
        <hr />
        <form role="form" method="post" action="/dashboard/tasks/add">
          
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="col-sm-6 col-12">
            <label>Name</label>
            <input type="text" class="form-control" name="task_name">
          </div>
          
          <div class="col-sm-6 col-12">
            <label>Description</label>
            <input type="text" class="form-control" name="task_description">
          </div>

          <div class="col-sm-6 col-12">
            <label>Due date</label>
            <input type="text" class="form-control datepicker" data-toggle="datepicker" id="due_date" name="due_date" aria-label="Due date">
          </div> 
          
          <div class="col-sm-6 col-xs-12">
            <label>Time start</label>
            <input type="text" class="form-control timepicker-start" id="due_time" name="due_time" aria-label="Time due">
          </div>          
          
            @hasanyrole('authenticated_user|administrator')
              @if(count($cases) > 0)  
                <div class="col-sm-6 col-12">
                  <label for="case_name">Case link</label>
                  @if(!isset($case))
                    <input type="hidden" name="case_id" />
                    <input type="text" name="case_name" class="form-control" placeholder="Case name" />
                  @else
                    <input type="hidden" name="case_id" value={{ $case->id }}" />
                    <input type="text" name="case_name" class="form-control" value="{{ $case->name }}" disabled />            
                  @endif
                </div>
              @endif          
              @if(count($contacts) > 0)
                <div class="col-sm-6 col-12">
                  <label for="contact_name">Contact/Client link</label>
                  @if(!isset($contact))            
                    <input type="hidden" name="contact_id" />
                    <input type="text" name="contact_name" class="form-control" placeholder="Contact name" />
                  @else
                    <input type="hidden" name="contact_id" value="{{ $contact->id }}" />
                    <input type="text" name="contact_name" class="form-control" disabled value="{{ $contact->first_name }} {{ $contact->last_name }}" />             
                  @endif
                </div>
              @endif
              @if(count($contacts) > 0)
                <div class="col-sm-6 col-12">
                  <label for="file_name">Share with client?</label>
                  <input type="checkbox" class="form-control" name="client_share" />
                </div>
              @endif
					  @endhasanyrole     
          
          <div class="clearfix"></div>
          <hr />
          
          <label class="ml-3">Categories</label>         
          <div class="col-sm-12 category-tags">
           <input name='tags' class="form-field-hide" placeholder='Enter some tags'>
          </div>
          
          <div class="col-12">
            <button type="submit" class="btn btn-primary mt-2 mb-2"><i class="fas fa-check"></i> Submit</button>
          </div>    
          
        </form>
      </div>
    </div>
  </div>
</div>

