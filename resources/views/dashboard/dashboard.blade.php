@extends('adminlte::page')

@section('content')

<div class="container dashboard home col-xs-12 offset-sm-2">
  <!--<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-briefcase"></i> Add case</a>       
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contacts-modal" href="#"><i class="fas fa-address-card"></i> Add contact</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#client-modal" href="#"><i class="fas fa-address-card"></i> Add client</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i class="fas fa-calendar-plus"></i> Add event</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#document-modal" href="#"><i class="fas fa-cloud-upload-alt"></i> Add document</a>
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#task-modal" href="#"><i class="fas fa-tasks"></i> Add Task</a>
	</nav>  	-->
  	@include('dashboard.includes.alerts')
  
  <div id="dashbaord-main">

  <div class="col-md-6 col-xs-12">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
              @if(Gravatar::exists($user->email))
              <div class="widget-user-image">
                <img class="img-circle" src="{{ Gravatar::get($user->email) }}" alt="User Avatar">
              </div>
              @endif
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">{{ $user->name }}</h3>
              <h5 class="widget-user-desc">{{ $settings->title }}</h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <li><a href="/dashboard/cases">Cases <span class="pull-right badge bg-blue">{{ count($cases) }}</span></a></li>
                <li><a href="/dashboard/tasks">Tasks <span class="pull-right badge bg-aqua">{{ $task_count }}</span></a></li>
                <li><a href="/dashboard/invoices">Invoices <span class="pull-right badge bg-green">{{ count($invoices) }}</span></a></li>
                <li><a href="/dashboard/calendar">Events <span class="pull-right badge bg-red">{{ count($events) }}</span></a></li>
              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
 </div>
    
      <div class="col-md-3 col-xs-12 hidden-xs">
      <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ count($clients) }}</h3>

              <p>Clients</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="/dashboard/clients" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
          </div>
      </div>
      <div class="col-md-3 col-xs-12 hidden-xs">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $task_count }}</h3>

              <p>Total tasks</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="/dashboard/tasklists" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
          </div>
      </div>
      <div class="col-md-3 col-xs-12 hidden-xs">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ count($events) }}</h3>

              <p>Events</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar-alt"></i>
            </div>
            <a href="/dashboard/calendar" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
          </div>
      </div>
      <div class="col-md-3 col-xs-12 hidden-xs">
      <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ count($invoices) }}</h3>

              <p>Invoices</p>
            </div>
            <div class="icon">
              <i class="fa fa-file-alt"></i>
            </div>
            <a href="/dashboard/invoices" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
          </div>
    </div>

<div class="col-md-6 col-xs-12">
  <div class="box box-warning">
    <div class="box-header with-border">
      <h3 class="box-title">{{ $firm->name }}</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
      <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body" style="">
      <p>Numbers are up in 2018, as we've kicked off a rally in style to raise nearly $150,000 since the beginning of the year!  Keep up the great work!</p>
      <p>- Robby</p>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</div>

      @if(count($tasklists) > 0)
    <div class="col-sm-6 col-xs-12 mb-4">
   <div class="box box-primary">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">All tasks</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list ui-sortable" data-widget="todo-list">
                @foreach($tasklists as $tasklist)
                  @if(count($tasklist->Dashboardtasks) > 0)
                  @foreach($tasklist->Dashboardtasks as $task)
                 
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
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                  </div>
                  </li>
                 
                  @endforeach
                  @endif
                @endforeach
                
              </ul>
            </div>
            <!-- /.box-body -->
          </div>

  </div> 
    @endif
   <div class="col-sm-6 col-xs-12 mb-4">
   <div class="panel panel-primary">
      <div class="panel-heading">
        <h2>
          <i class="fas fa-cogs"></i> Quick note
        </h2>
     </div>
     <div class="panel-body">
        <form>
          <label>Note</label>
          <textarea name="quick_note" class="form-control"></textarea>
          <label>Relation</label>
          <input type="text" name="relation" class="form-control mt-2 mb-2" />
          <button type="submit" class="btn btn-primary form-control">Submit</button>
       </form>
     </div>
  </div>   
  </div> 
 

 
</div>
</div>

@include('dashboard.includes.contact-modal')
@include('dashboard.includes.case-modal')
@include('dashboard.includes.client-modal')
@include('dashboard.includes.event-modal')
@include('dashboard.includes.document-modal')
@include('dashboard.includes.task-modal')

<script src="{{ asset('js/autocomplete.js') }}"></script>

<script type="text/javascript">
var cases = {!! json_encode($cases->toArray()) !!};
var clients = {!! json_encode($clients->toArray()) !!};
var contacts = {!! json_encode($contacts->toArray()) !!};	

  for(var i = 0; i<cases.length; i++){
    //cases[i].data[i].category = 'case'
	  cases[i].id = cases[i]['id'];
	  cases[i].value = cases[i]['name'];
    cases[i].data = 'case';
	}

	for(var i = 0; i<clients.length; i++){
		clients[i].id = clients[i]['id'];
		clients[i].value = clients[i]['first_name'] + " " + clients[i]['last_name'];
    clients[i].data = 'client';
	}
  
	for(var i = 0; i<contacts.length; i++){
    contacts[i].data = 'contact';
		contacts[i].id = contacts[i]['id'];
		contacts[i].value = contacts[i]['first_name'] + " " + contacts[i]['last_name'];
	}	
  
	arr = cases.concat(clients, contacts);

  $('input[name="relation"]').autocomplete({
    lookup: arr,
    width: 'flex',
    triggerSelectOnValidInput: true,
    groupBy: 'data',
    onSelect: function (suggestion) {
      var thehtml = '<strong>Case '+suggestion.id+':</strong> ' + suggestion.value + ' ';
      //alert(thehtml);
      var $this = $(this);
      $('#outputcontent').html(thehtml);
      $this.prev().val(suggestion.data);
    }
  });
  
	$('input[name="client_name"]').autocomplete({
    lookup: clients,
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