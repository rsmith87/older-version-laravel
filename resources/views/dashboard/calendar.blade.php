@extends('layouts.dashboard')

@section('content')

<div class="container fill calendar dashboard col-sm-10 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#calendar-modal" href="#"><i class="fas fa-calendar-plus"></i> Add event</a>
    @if(!$role)
    <a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-calendar-plus"></i> <i class="fas fa-user"></i> Assign events to user</a>
    @endif
  </nav>  
	
	@include('dashboard.includes.alerts')

	
	<h1 class="mb-5 ml-3"><i class="fa fa-calendar-alt"></i> Calendar</h1>
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

           <div class="col-sm-6 col-xs-12">
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text">Name</span>
               </div>
               <input type="text" class="form-control" name="name" aria-label="Event name">
             </div>
           </div>    
           <div class="col-sm-6 col-xs-12">
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text">Description</span>
               </div>
               <input type="text" class="form-control" name="description" aria-label="Event description">
             </div>
           </div> 
           <div class="col-sm-6 col-xs-12">
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text">Start date</span>
               </div>
               <input type="text" class="form-control datepicker" data-toggle="datepicker" id="start_date" name="start_date" aria-label="Start date">
             </div>
           </div>
           <div class="col-sm-6 col-xs-12">
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text">End date</span>
               </div>
               <input type="text" class="form-control datepicker" data-toggle="datepicker" id="end_date" name="end_date" aria-label="End date">
             </div>
           </div> 
           <div class="col-sm-6 col-xs-12">
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text">Time start</span>
               </div>
               <input type="text" class="form-control timepicker-start" id="start_time" name="start_time" aria-label="Start time">
             </div>
           </div>  
           <div class="col-sm-6 col-xs-12">
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text">Time end</span>
               </div>
               <input type="text" class="form-control timepicker-end" id="end_time" name="end_time" aria-label="End time">
             </div>
           </div>    
           <div class="col-12">
             
         
           <button class="btn btn-primary"><i class="fas fa-check"></i> Submit</button>
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