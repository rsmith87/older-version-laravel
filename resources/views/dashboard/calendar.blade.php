@extends('layouts.dashboard')

@section('content')

<div class="container fill calendar dashboard col-sm-10 col-12 offset-sm-2">
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#calendar-modal" href="#"><i class="fas fa-calendar-plus"></i> Add event</a>
		<a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-calendar-plus"></i> <i class="fas fa-user"></i> Assign events to user</a>
	</nav>  

	<h1 class="mb-2 ml-3">
		<i class="fa fa-calendar-alt"></i> Calendar
	</h1>
	<div class="clearfix"></div>
	<p class="ml-3 mb-2">Calendar shows all of your events.  Click on an event to edit or create an event above!</p>							
	@include('dashboard.includes.alerts')
	@if (count($events) === 0)
		<div class="alert alert-warning alert-dismissible fade in" role="alert">
			No events for this user, yet! <strong>Add a new event above!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	@endif
	
<div id="calendar"></div>

<div class="modal fade" id="calendar-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
				<i class="fas fa-calendar-plus"></i> Create an event
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form method="post" action="/dashboard/calendar/event/add">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-sm-6 col-12">
						<label>Name</label>    
						<input type="text" class="form-control" name="name" aria-label="Event name">
					</div>
					<div class="col-sm-6 col-12">
						<label>Description</label>
						<input type="text" class="form-control" name="description" aria-label="Event description">
					</div>
					<div class="col-sm-6 col-12">
						<label>Start date</label>
						<input type="text" class="form-control datepicker" data-toggle="datepicker" id="start_date" name="start_date" aria-label="Start date">
					</div>
					<div class="col-sm-6 col-12">
						<label>End date</label>
						<input type="text" class="form-control datepicker" data-toggle="datepicker" id="end_date" name="end_date" aria-label="End date">
					</div>
					<div class="col-sm-6 col-12">
						<label>Time start</label>
						<input type="text" class="form-control timepicker-start" id="start_time" name="start_time" aria-label="Start time">
					</div>
					<div class="col-sm-6 col-12">
						<label>Time end</label>
						<input type="text" class="form-control timepicker-end" id="end_time" name="end_time" aria-label="End time">
					</div>
					<div class="col-12">
						<button class="btn btn-primary mt-4"><i class="fas fa-check"></i> Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

  
<script type="text/javascript">
var events =  {!! json_encode($events->toArray()) !!};  
  
  for(var i = 0; i<events.length; i++){
	  events[i].id = events[i]['id'];
	  events[i].title = events[i]['name'];
    events[i].start = Date.parse(events[i]['start_date']);
    events[i].end = Date.parse(events[i]['end_date']);

}  
  
</script>

@endsection