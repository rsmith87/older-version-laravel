@extends('layouts.dashboard')

@section('content')

<div class="container fill calendar dashboard col-sm-10 col-12 offset-sm-2">
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i class="fas fa-calendar-plus"></i> Add event</a>
		@hasanyrole('authenticated_user|administrator')
		<a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-calendar-plus"></i> <i class="fas fa-user"></i> Assign events to user</a>
		<a class="nav-item nav-link btn btn-info" href="/dashboard/calendar/events"><i class="fas fa-calendar-plus"></i> Client events</a>
		@endhasrole
		<a class="nav-item nav-link btn btn-info" href="/dashboard/calendar/events/denied"><i class="fas fa-calendar-plus"></i> Denied events</a>

	</nav>  

	<h1 class="mb-2 ml-3">
		<i class="fa fa-calendar-alt"></i> Calendar
	</h1>
	<div class="clearfix"></div>
	<p class="ml-3 mb-2">Calendar shows all of your events.  Click on an event to edit or create an event above!</p>							
	@include('dashboard.includes.alerts')
	@if (count($events) === 0)
		<div class="alert alert-warning alert-dismissible fade in mt-3 mb-3" role="alert">
			No events for this user, yet! <strong>Add a new event above!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	@endif
	
<div id="calendar" class="pl-3 pr-4"></div>

@include('dashboard.includes.event-modal')

<script type="text/javascript">
var events =  {!! json_encode($events->toArray()) !!};  
var u_id = {!! json_encode($user['id']) !!}
var user = {!! json_encode($user) !!} 
  for(var i = 0; i<events.length; i++){
		
	  events[i].id = events[i]['id'];
	  events[i].title = events[i]['name'];
		events[i].client;
		console.log(events);
		if(events[i].approved == '0'){
			events[i].color = 'gray';
		} else if(events[i].approved == '2') {
			events[i].color = 'red';
		}
		//events[i].color = 
		
    events[i].start = Date.parse(events[i]['start_date']);
    events[i].end = Date.parse(events[i]['end_date']);
	} 
	var $editable = true;
</script>
	
@hasanyrole('client')
<script type="text/javascript">
	for(var i=0; i<events.length; i++){
		if(events[i]['co_id'] != u_id){
		  events[i].title = "Blocked";			
			events[i].color= "orange";
		} else {
			events[i].title = events[i]['name'];
			//events[i].color = 'blue';
		}

	}
 var $editable = false;
</script>
@endhasrole

	
<script type="text/javascript">
	

  	
</script>
	
	
@endsection