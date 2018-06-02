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
    
      <div class="col-md-3 col-xs-12">
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
      <div class="col-md-3 col-xs-12">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $task_count }}</h3>

              <p>Total tasks</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="/dashboard/tasks" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
          </div>
      </div>
      <div class="col-md-3 col-xs-12">
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
      <div class="col-md-3 col-xs-12">
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
  <div class="col-sm-6 col-xs-12 mb-4">
<div class="box box-info">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-envelope"></i>

              <h3 class="box-title">Quick Email</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body">
              <form action="#" method="post">
                <div class="form-group">
                  <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" placeholder="Subject">
                </div>
                <div>
                  <ul class="wysihtml5-toolbar" style=""><li class="dropdown">
  <a class="btn btn-default dropdown-toggle " data-toggle="dropdown">
    
      <span class="glyphicon glyphicon-font"></span>
    
    <span class="current-font">Normal text</span>
    <b class="caret"></b>
  </a>
  <ul class="dropdown-menu">
    <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="p" tabindex="-1" href="javascript:;" unselectable="on">Normal text</a></li>
    <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" tabindex="-1" href="javascript:;" unselectable="on">Heading 1</a></li>
    <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" tabindex="-1" href="javascript:;" unselectable="on">Heading 2</a></li>
    <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3" tabindex="-1" href="javascript:;" unselectable="on">Heading 3</a></li>
    <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h4" tabindex="-1" href="javascript:;" unselectable="on">Heading 4</a></li>
    <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h5" tabindex="-1" href="javascript:;" unselectable="on">Heading 5</a></li>
    <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h6" tabindex="-1" href="javascript:;" unselectable="on">Heading 6</a></li>
  </ul>
</li>
<li>
  <div class="btn-group">
    <a class="btn  btn-default" data-wysihtml5-command="bold" title="CTRL+B" tabindex="-1" href="javascript:;" unselectable="on">Bold</a>
    <a class="btn  btn-default" data-wysihtml5-command="italic" title="CTRL+I" tabindex="-1" href="javascript:;" unselectable="on">Italic</a>
    <a class="btn  btn-default" data-wysihtml5-command="underline" title="CTRL+U" tabindex="-1" href="javascript:;" unselectable="on">Underline</a>
    
    <a class="btn  btn-default" data-wysihtml5-command="small" title="CTRL+S" tabindex="-1" href="javascript:;" unselectable="on">Small</a>
    
  </div>
</li>

<li>
  <div class="btn-group">
    <a class="btn  btn-default" data-wysihtml5-command="insertUnorderedList" title="Unordered list" tabindex="-1" href="javascript:;" unselectable="on">
    
      <span class="glyphicon glyphicon-list"></span>
    
    </a>
    <a class="btn  btn-default" data-wysihtml5-command="insertOrderedList" title="Ordered list" tabindex="-1" href="javascript:;" unselectable="on">
    
      <span class="glyphicon glyphicon-th-list"></span>
    
    </a>
    <a class="btn  btn-default" data-wysihtml5-command="Outdent" title="Outdent" tabindex="-1" href="javascript:;" unselectable="on">
    
      <span class="glyphicon glyphicon-indent-right"></span>
    
    </a>
    <a class="btn  btn-default" data-wysihtml5-command="Indent" title="Indent" tabindex="-1" href="javascript:;" unselectable="on">
    
      <span class="glyphicon glyphicon-indent-left"></span>
    
    </a>
  </div>
</li>
<li>
  <a class="btn  btn-default" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="blockquote" data-wysihtml5-display-format-name="false" tabindex="-1" href="javascript:;" unselectable="on">
    
      <span class="glyphicon glyphicon-quote"></span>
    
  </a>
</li>
<li>
  <div class="bootstrap-wysihtml5-insert-link-modal modal fade" data-wysihtml5-dialog="createLink">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Insert link</h3>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input value="http://" class="bootstrap-wysihtml5-insert-link-url form-control" data-wysihtml5-dialog-field="href">
          </div> 
          <div class="checkbox">
            <label> 
              <input type="checkbox" class="bootstrap-wysihtml5-insert-link-target" checked="">Open link in new window
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <a class="btn btn-default" data-dismiss="modal" data-wysihtml5-dialog-action="cancel" href="#">Cancel</a>
          <a href="#" class="btn btn-primary" data-dismiss="modal" data-wysihtml5-dialog-action="save">Insert link</a>
        </div>
      </div>
    </div>
  </div>
  <a class="btn  btn-default" data-wysihtml5-command="createLink" title="Insert link" tabindex="-1" href="javascript:;" unselectable="on">
    
      <span class="glyphicon glyphicon-share"></span>
    
  </a>
</li>
<li>
  <div class="bootstrap-wysihtml5-insert-image-modal modal fade" data-wysihtml5-dialog="insertImage">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Insert image</h3>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input value="http://" class="bootstrap-wysihtml5-insert-image-url form-control" data-wysihtml5-dialog-field="src">
          </div> 
        </div>
        <div class="modal-footer">
          <a class="btn btn-default" data-dismiss="modal" data-wysihtml5-dialog-action="cancel" href="#">Cancel</a>
          <a class="btn btn-primary" data-dismiss="modal" data-wysihtml5-dialog-action="save" href="#">Insert image</a>
        </div>
      </div>
    </div>
  </div>
  <a class="btn  btn-default" data-wysihtml5-command="insertImage" title="Insert image" tabindex="-1" href="javascript:;" unselectable="on">
    
      <span class="glyphicon glyphicon-picture"></span>
    
  </a>
</li>
</ul><textarea class="textarea" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px; display: none;" placeholder="Message"></textarea><input type="hidden" name="_wysihtml5_mode" value="1"><iframe class="wysihtml5-sandbox" security="restricted" allowtransparency="true" frameborder="0" width="0" height="0" marginwidth="0" marginheight="0" style="display: inline-block; background-color: rgb(255, 255, 255); border-collapse: separate; border-color: rgb(221, 221, 221); border-style: solid; border-width: 1px; clear: none; float: none; margin: 0px; outline: rgb(51, 51, 51) none 0px; outline-offset: 0px; padding: 10px; position: static; top: auto; left: auto; right: auto; bottom: auto; z-index: auto; vertical-align: baseline; text-align: start; box-sizing: border-box; box-shadow: none; border-radius: 0px; width: 100%; height: 125px;"></iframe>
                </div>
              </form>
            </div>
            <div class="box-footer clearfix">
              <button type="button" class="pull-right btn btn-default" id="sendEmail">Send
                <i class="fa fa-arrow-circle-right"></i></button>
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