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
                <li><a href="/dashboard/tasklists">Tasks <span class="pull-right badge bg-aqua">{{ $task_count }}</span></a></li>
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
@if(!empty($firm_message) || $firm_message === "")
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
      <p>{{ $firm_message->firm_message }}</p>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</div>
      @endif

      @if(count($tasklists) > 0)
    <div class="col-sm-6 col-xs-12 mb-4">
   <div class="box box-primary">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Tasklists</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list ui-sortable" data-widget="todo-list">

                @foreach($tasklists as $tasklist)
				  <span class="text">{{ $tasklist->task_list_name }}</span>

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


  <div class="col-sm-6 col-xs-12">
    <div class="box box-solid bg-green-gradient">
      <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="fa fa-calendar"></i>

        <h3 class="box-title">Calendar</h3>
        <!-- tools box -->
        <div class="pull-right box-tools">
          <!-- button with a dropdown -->
          <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bars"></i></button>
            <ul class="dropdown-menu pull-right" role="menu">
              <li><a href="#" data-toggle="modal" data-target="#event-modal" >Add new event</a></li>
              <li class="divider"></li>
              <li><a href="/dashboard/calendar">View calendar</a></li>
            </ul>
          </div>
          <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
          </button>
        </div>
        <!-- /. tools -->
      </div>
      <!-- /.box-header -->
      <div class="box-body no-padding">
        <!--The calendar -->
        <div id="calendar" style="width: 100%"><div class="datepicker datepicker-inline"><div class="datepicker-days" style=""><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">July 2018</th><th class="next">»</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="old day" data-date="1529798400000">24</td><td class="old day" data-date="1529884800000">25</td><td class="old day" data-date="1529971200000">26</td><td class="old day" data-date="1530057600000">27</td><td class="old day" data-date="1530144000000">28</td><td class="old day" data-date="1530230400000">29</td><td class="old day" data-date="1530316800000">30</td></tr><tr><td class="day" data-date="1530403200000">1</td><td class="day" data-date="1530489600000">2</td><td class="day" data-date="1530576000000">3</td><td class="day" data-date="1530662400000">4</td><td class="day" data-date="1530748800000">5</td><td class="day" data-date="1530835200000">6</td><td class="day" data-date="1530921600000">7</td></tr><tr><td class="day" data-date="1531008000000">8</td><td class="day" data-date="1531094400000">9</td><td class="day" data-date="1531180800000">10</td><td class="day" data-date="1531267200000">11</td><td class="day" data-date="1531353600000">12</td><td class="day" data-date="1531440000000">13</td><td class="day" data-date="1531526400000">14</td></tr><tr><td class="day" data-date="1531612800000">15</td><td class="day" data-date="1531699200000">16</td><td class="day" data-date="1531785600000">17</td><td class="day" data-date="1531872000000">18</td><td class="day" data-date="1531958400000">19</td><td class="day" data-date="1532044800000">20</td><td class="day" data-date="1532131200000">21</td></tr><tr><td class="day" data-date="1532217600000">22</td><td class="day" data-date="1532304000000">23</td><td class="day" data-date="1532390400000">24</td><td class="day" data-date="1532476800000">25</td><td class="day" data-date="1532563200000">26</td><td class="day" data-date="1532649600000">27</td><td class="day" data-date="1532736000000">28</td></tr><tr><td class="day" data-date="1532822400000">29</td><td class="day" data-date="1532908800000">30</td><td class="day" data-date="1532995200000">31</td><td class="new day" data-date="1533081600000">1</td><td class="new day" data-date="1533168000000">2</td><td class="new day" data-date="1533254400000">3</td><td class="new day" data-date="1533340800000">4</td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">2018</th><th class="next">»</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month focused">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">2010-2019</th><th class="next">»</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year focused">2018</span><span class="year">2019</span><span class="year new">2020</span></td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div><div class="datepicker-decades" style="display: none;"><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">2000-2090</th><th class="next">»</th></tr></thead><tbody><tr><td colspan="7"><span class="decade old">1990</span><span class="decade">2000</span><span class="decade focused">2010</span><span class="decade">2020</span><span class="decade">2030</span><span class="decade">2040</span><span class="decade">2050</span><span class="decade">2060</span><span class="decade">2070</span><span class="decade">2080</span><span class="decade">2090</span><span class="decade new">2100</span></td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div><div class="datepicker-centuries" style="display: none;"><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">2000-2900</th><th class="next">»</th></tr></thead><tbody><tr><td colspan="7"><span class="century old">1900</span><span class="century focused">2000</span><span class="century">2100</span><span class="century">2200</span><span class="century">2300</span><span class="century">2400</span><span class="century">2500</span><span class="century">2600</span><span class="century">2700</span><span class="century">2800</span><span class="century">2900</span><span class="century new">3000</span></td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div></div></div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer text-black">
        <div class="row">
          <div class="col-sm-6">
            <!-- Progress bars -->
            <div class="clearfix">
              <span class="pull-left">Task #1</span>
              <small class="pull-right">90%</small>
            </div>
            <div class="progress xs">
              <div class="progress-bar progress-bar-green" style="width: 90%;"></div>
            </div>

            <div class="clearfix">
              <span class="pull-left">Task #2</span>
              <small class="pull-right">70%</small>
            </div>
            <div class="progress xs">
              <div class="progress-bar progress-bar-green" style="width: 70%;"></div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <div class="clearfix">
              <span class="pull-left">Task #3</span>
              <small class="pull-right">60%</small>
            </div>
            <div class="progress xs">
              <div class="progress-bar progress-bar-green" style="width: 60%;"></div>
            </div>

            <div class="clearfix">
              <span class="pull-left">Task #4</span>
              <small class="pull-right">40%</small>
            </div>
            <div class="progress xs">
              <div class="progress-bar progress-bar-green" style="width: 40%;"></div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
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