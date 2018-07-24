@extends('adminlte::page')

@section('content')

  <div class="container fill calendar dashboard col-sm-12 offset-sm-2">
	<nav class="nav nav-pills">
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i
				class="fas fa-calendar-plus"></i> Add event</a>
	  @hasanyrole('authenticated_user|administrator')
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#user-modal" href="#"><i
				class="fas fa-calendar-plus"></i> <i class="fas fa-user"></i> Assign events to user</a>
	  <a class="nav-item nav-link btn btn-info" href="/dashboard/calendar/events"><i class="fas fa-user"></i> <i
				class="fas fa-calendar-alt"></i> Client events</a>
	  @endhasrole
	  <a class="nav-item nav-link btn btn-info" href="/dashboard/calendar/events/denied"><i
				class="fas fa-calendar-times"></i> Denied events</a>

	</nav>

	<h1 class="mb-2 ml-3">
	  <i class="fa fa-calendar-alt"></i> Calendar
	</h1>

	<div class="clearfix"></div>
	<p class="ml-3 mb-2">Calendar shows all of your events. Create an customized event above or use some of the default
	  events added.</p>
	<p>(Times for draggable events set to 1 hour. To make these times more granular, adjust the event on the weekly,
	  daily or hourly view)</p>
	@include('dashboard.includes.alerts')
	@if (count($events) === 0)
	  <div class="alert alert-warning alert-dismissible fade in mt-3 mb-3" role="alert">
		No events for this user, yet! <strong>Add a new event above!</strong>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	@endif

	<div>


	  <!-- Main content -->
	  <section class="content">
		<div class="row">
		  <div class="col-md-3">
			<div class="box box-solid">
			  <div class="box-header with-border">
				<h4 class="box-title">Draggable Events</h4>
			  </div>
			  <div class="box-body">
				<!-- the events -->
				<div id="external-events">
				  <div class="external-event bg-green ui-draggable ui-draggable-handle">Lunch</div>
				  <div class="external-event bg-yellow ui-draggable ui-draggable-handle">Blocker</div>
				  <div class="external-event bg-aqua ui-draggable ui-draggable-handle">Research</div>
				  <div class="external-event bg-light-blue ui-draggable ui-draggable-handle">Office hour booked</div>
				  <div class="checkbox">
					<label for="drop-remove">
					  <input type="checkbox" id="drop-remove">
					  remove after drop
					</label>
				  </div>
				</div>
			  </div>
			  <!-- /.box-body -->
			</div>
			<!-- /. box -->
			<div class="box box-solid">
			  <div class="box-header with-border">
				<h3 class="box-title">Create Event</h3>
			  </div>
			  <div class="box-body">
				<div class="btn-group">
				  <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
				  <ul class="fc-color-picker" id="color-chooser">
					<li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
					<li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
				  </ul>
				</div>
				<!-- /btn-group -->
				<div class="input-group">
				  <input id="new-event" type="text" class="form-control" placeholder="Event Title">

				  <div class="input-group-btn">
					<button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
				  </div>
				  <!-- /btn-group -->
				</div>
				<!-- /input-group -->
			  </div>
			</div>
		  </div>
		  <!-- /.col -->
		  <div class="col-md-9">
			<div class="box box-primary">
			  <div class="box-body no-padding">
				<!-- THE CALENDAR -->
				<div id="calendar" class="fc fc-unthemed fc-ltr"></div>
				<!-- /.box-body -->
			  </div>
			  <!-- /. box -->
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
	  </section>
	  <!-- /.content -->
	</div>

	@include('dashboard.includes.event-modal')

	<script type="text/javascript">
	  var events =  {!! json_encode($events->toArray()) !!};
	  var show_task_calendar = {!! count($show_task_calendar) > 0 ? json_encode($show_task_calendar->toArray()) : 0 !!};
	  var u_id =
			  {!! json_encode($user['id']) !!}
	  var user =
	  {!! json_encode($user) !!}
	  if (show_task_calendar.length > 0) {
		//events.concat(show_task_calendar);
		events = $.merge(events, show_task_calendar);
	  }

	  //console.log(show_task_calendar);
	  for (var i = 0; i < events.length; i++) {

		events[i].id = events[i]['id'];
		events[i].title = events[i]['name'];
		//events[i].client;
		events[i].start = Date.parse(events[i]['start_date']);
		events[i].end = Date.parse(events[i]['end_date']);

		if (events[i].approved == '0') {
		  events[i].color = 'gray';
		} else if (events[i].approved == '2') {
		  events[i].color = 'red';
		}

		if (events[i].hasOwnProperty('task_name')) {
		  events[i].title = events[i]['task_name'];
		  events[i].start = events[i]['due'];
		  events[i].end = events[i]['due'];
		  events[i].color = "purple";
		}
	  }
	  var $editable = true;

	</script>

	@hasanyrole('client')
	<script type="text/javascript">
	  for (var i = 0; i < events.length; i++) {
		if (events[i]['co_id'] != u_id) {
		  events[i].title = "Blocked";
		  events[i].color = "orange";
		} else {
		  events[i].title = events[i]['name'];
		  //events[i].color = 'blue';
		}

	  }
	  var $editable = false;
	</script>
	@endhasrole


@endsection