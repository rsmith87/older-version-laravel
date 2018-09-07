<div class="modal fade" id="task-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h3>
          <i class="fas fa-tasks"></i> Create a {{ null !== Request::segment(3) ? "Task" : "Task board" }}
        </h3>
        <div class="clearfix"></div>
        <hr />
        <form role="form" method="post" action="{{ null !== Request::segment(3) ?'/dashboard/tasklists/task/add' : '/dashboard/tasklists/add' }} ">
          
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          @if(null !== Request::segment(3) && Request::segment(3) != 'completed')
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
            <input type="text" class="form-control dp" data-toggle="dp" id="due_date" name="due_date" aria-label="Due date">
          </div> 
          
          <div class="col-sm-6 col-xs-12">
            <label><i class="fas fa-clock"></i> Due time</label>
            <input type="text" class="form-control timepicker-start" id="due_time" name="due_time" aria-label="Time due">
          </div>          
          @if(null !== Request::segment(3) && Request::segment(3) != 'completed')
            @hasanyrole('authenticated_user|administrator')
              @if(count($case) > 0)
                <div class="col-sm-6 col-12">
                  <label for="case_name"><i class="fas fa-briefcase"></i> Case link</label>

                        <input type="hidden" name="case_id" value="{{ $case->id }}" />
                    <input type="text" name="case_name" class="form-control" value="{{ $case->name }}" />

                </div>
              @else
                    <div class="col-sm-6 col-12">
                        <label for="case_name"><i class="fas fa-briefcase"></i> Case link</label>
                    <input type="hidden" name="case_id" />
                    <input type="text" name="case_name" class="form-control" placeholder="Case name" />
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

              @endif
					  @endhasanyrole     
          @endif
          <div class="clearfix"></div>


            @if(Request::segment(3) === "" || Request::segment(3) === null)
              <hr />

            <label class="ml-3"><i class="fas fa-tags"></i> Categories</label>
          <div class="col-sm-12 tasklist-categories">
            <select class="js-category-tasklist" name="categories[]" multiple="multiple">

            </select>
          </div>

           @endif

          @if(null === Request::segment(3))
          <div class="col-sm-6 col-xs-12">
            <input type="checkbox" name="show_dashboard" class="form-control">
            <label>  Show on dashboard?</label>

          </div>
          @endif
     
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary mt-2 mb-2"><i class="fas fa-check"></i> Submit</button>
          </div>         
        </form>
      </div>
    </div>
  </div>
</div>

