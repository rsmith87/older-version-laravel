<div class="modal fade" id="task-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h3>
          <i class="fas fa-tasks"></i> Create a {{ null !== Request::segment(3) ? "Task" : "Task list" }} 
        </h3>
        <div class="clearfix"></div>
        <hr />
        <form role="form" method="post" action="{{ null !== Request::segment(3) ?'/dashboard/tasklists/task/add' : '/dashboard/tasklists/add' }} ">
          
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          @if(null !== Request::segment(3))
          <input type="hidden" name="tl_uuid" value="{{ $task_list->task_list_uuid }}" />
          @endif

          <div class="col-sm-6 col-12">
            <label>Name</label>
            <input type="text" class="form-control" name="task_name">
          </div>
          
          <div class="col-sm-6 col-12">
            <label>Description</label>
            <input type="text" class="form-control" name="task_description">
          </div>

          <div class="col-sm-6 col-12">
            <label><i class="fas fa-calendar-alt"></i> Due date</label>
            <input type="text" class="form-control datepicker" data-toggle="datepicker" id="due_date" name="due_date" aria-label="Due date">
          </div> 
          
          <div class="col-sm-6 col-xs-12">
            <label><i class="fas fa-clock"></i> Due time</label>
            <input type="text" class="form-control timepicker-start" id="due_time" name="due_time" aria-label="Time due">
          </div>          
          @if(null !== Request::segment(3))
            @hasanyrole('authenticated_user|administrator')
              @if(count($cases) > 0)  
                <div class="col-sm-6 col-12">
                  <label for="case_name"><i class="fas fa-briefcase"></i> Case link</label>
                  @if(!isset($case))
                    <input type="hidden" name="case_id" />
                    <input type="text" name="case_name" class="form-control" placeholder="Case name" />
                  @else
                    <input type="hidden" name="case_id" value={{ $case->id }}" />
                    <input type="text" name="case_name" class="form-control" value="{{ $case->name }}" />            
                  @endif
                </div>
              @endif          
              @if(count($contacts) > 0)
                <div class="col-sm-6 col-12">
                  <label for="contact_name"><i class="fas fa-user"></i> Client link</label>
                  @if(!isset($contact))            
                    <input type="hidden" name="contact_id" />
                    <input type="text" name="contact_name" class="form-control" placeholder="Contact name" />
                  @else
                    <input type="hidden" name="contact_id" value="{{ $contact->id }}" />
                    <input type="text" name="contact_name" class="form-control" value="{{ $contact->first_name }} {{ $contact->last_name }}" />             
                  @endif
                </div>

                <div class="col-sm-6 col-12">
                  <label for="file_name">Share with client?</label>
                  <input type="checkbox" class="form-control" name="client_share" />
                </div>
              @endif
					  @endhasanyrole     
          @endif
          <div class="clearfix"></div>
          <hr class="{{ null === Request::segment(3) ? 'd-none' : ''  }}" />
          
          <div class="{{ null === Request::segment(3) ? 'd-none' : ''  }}">
            
          <label class="ml-3"><i class="fas fa-tags"></i> Categories</label>         
          <div class="col-sm-12 tasklist-categories">
            <select class="js-category-tasklist" name="categories[]" multiple="multiple">

            </select>
          </div>
         
          </div>
     
          <div class="col-12">
            <button type="submit" class="btn btn-primary mt-2 mb-2"><i class="fas fa-check"></i> Submit</button>
          </div>         
        </form>
      </div>
    </div>
  </div>
</div>

