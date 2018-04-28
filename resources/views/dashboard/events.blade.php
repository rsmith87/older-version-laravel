@extends('layouts.dashboard')

@section('content')

<div class="container fill calendar event dashboard col-sm-10 col-12 offset-sm-2">
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i class="fas fa-calendar-plus"></i> Add event</a>
		@hasanyrole('authenticated_user|administrator')
			<a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-calendar-plus"></i> <i class="fas fa-user"></i> Assign events to user</a>
			<a class="nav-item nav-link btn btn-info" href="/dashboard/calendar/events"><i class="fas fa-calendar-plus"></i> Client events</a>
		@endhasrole
		<a class="nav-item nav-link btn btn-info" href="/dashboard/calendar/events/denied"><i class="fas fa-calendar-plus"></i> Denied events</a>
	</nav>  	

	<h1 class="mb-2 ml-3">
		<i class="fa fa-calendar-alt"></i> {{ $title }}
	</h1>
	<div class="clearfix"></div>
	<p class="ml-3 mb-2">Events by your clients will be shown below.  Click to approve or deny the event.</p>							
	@include('dashboard.includes.alerts')
	@if(!Request::url()==='dashboard/calendar/events' || !Request::url()==='dashboard/calendar/events/denied') {
		@if (count($events) === 0)
			<div class="alert alert-warning alert-dismissible fade in mt-3 mb-3" role="alert">
				No events for this user, yet! <strong>Add a new event above!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif    
	@endif
	


				@if (count($events) > 0)		
					<table id="main" class="table table-responsive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
						<thead> 
							<tr> 
								<th>id</th>
								<th>Name</th>
								<th>Description</th>
								<th>Time</th>
								<th>Approved</th>
							</tr> 
						</thead> 
						<tbody> 			 
						@foreach($events as $event)
							<tr>
						
								<td>{{ $event->id }}</td>
								<td>{{ $event->name }}</td>
								<td>{{ $event->description }}</td> 
								<td>{{ $event->start_date }} - {{ $event->end_date }}</td>
								<td>{{ ucfirst($event->approved ? "yes":"no") }}</td>
							</tr> 
						@endforeach
						</tbody> 
					</table>
			@else
			<p class="ml-3 mt-5">
				No appointment requests! Huzzah!
			</p>
				@endif
</div>


@include('dashboard.includes.event-modal')

@hasanyrole('authenticated_user|administrator')
@foreach($events as $event)
<div class="modal fade" id="event-modal-{{ $event->id }}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
				<i class="fas fa-calendar-plus"></i> Approve or deny event
				</h3>
				<div class="clearfix"></div>
				<hr />

					<div class="col-12">
					<p>
						No current blockers
						</p>
					</div>
					<div class="col-sm-6 col-12">
						<label>Name</label>    
						<p>
							{{ $event->name }}
						</p>
						<label>Description</label>
						<p>
							{{ $event->description }}
						</p>
						@if(!empty($event->Contact))
						<label>Contact</label>
						<p>
							{{ $event->Contact['first_name'] }}  {{ $event->Contact['last_name'] }}
						</p>
						@endif
					</div>
					<div class="col-sm-6 col-12">
						<label>Start date</label>
						<p>
							{{ $event->start_date }}	
						</p>
						<label>End date</label>
						<p>
							{{ $event->end_date }}
						</p>
					</div>
				
					<div class="clearfix"></div>
					<div class="action-buttons">
						<div class="col-sm-6 col-12">
							<form method="post" action="/dashboard/calendar/events/{{ $event->id }}/approve">		
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="{{ $event->id }}">							
								<button href="" type="submit" class="btn btn-primary btn-lg btn-block float-left approve"><i class="fas fa-check"></i> Approve</button>
							</form>						
						</div>
						<div class="col-sm-6 col-12">
							<form method="POST" action="/dashboard/calendar/events/{{ $event->id }}/deny">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="{{ $event->id }}">									
								<button href="" class="btn btn-danger btn-lg btn-block deny float-left"><i class="fas fa-times"></i> Deny</button>								
							</form>
						</div>
					</div>
					
			</div>
		</div>
	</div>
</div>
@endforeach
@endhasrole
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