@extends('adminlte::page')

@section('content')

<div class="container dashboard case col-sm-10 col-12 offset-sm-2">
	<nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" href="/dashboard/cases"><i class="fas fa-arrow-left"></i> Back to cases</a>  
		<a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#case-edit-modal" href="#"><i class="fas fa-balance-scale"></i> Edit case</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target='#add-hours-modal' href='#'><i class="fas fa-clock"></i> Add hours worked</a>
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#add-notes-modal" href="#"><i class="fas fa-sticky-note"></i> Add note</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i class="fas fa-calendar-plus"></i> Add event</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#document-modal" href="#"><i class="fas fa-cloud-upload-alt"></i> Add document</a> 
		@if(!empty($case->Contacts))		
		@foreach($case->Contacts as $contact)
			@if($contact->is_client)	
				<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#payment-modal-full" href="#"><i class="fas fa-dollar-sign"></i> Bill {{ $contact->first_name }} {{ $contact->last_name }}</a>
		  @endif
		@endforeach
		@endif
		@if(count($order) > 0)
		<a class="nav-item nav-link btn btn-info" href="/dashboard/invoices"><i class="fa fa-file"></i> View invoices</a>
		@endif
    <a class="nav-item nav-link btn btn-info" href="/dashboard/cases/case/{{ $case->id }}/timeline"><i class="fas fa-heartbeat"></i> View timeline</a>    

	</nav> 	

	<div class="panel panel-default">
		<div class="panel-heading" style="overflow:hidden;">
			<h1 class="pull-left ml-3 mt-4 mb-2">
				<i class="fas fa-balance-scale"></i> Case timeline
			</h1>
			<div class="clearfix"></div>
			<p class="ml-3 mb-4">
				Case timeline for {{ $case->name }}
			</p>							
			@include('dashboard.includes.alerts')
		</div>
		<div class="panel-body">
				<div class="col-12">
				  <section class="content">

					<!-- row -->
					<div class="row">
					  <div class="col-md-12">
						<!-- The time line -->
						<ul class="timeline">
						  <!-- timeline time label -->
						  <li class="time-label">
                  <span class="bg-red">
                    10 Feb. 2014
                  </span>
							@foreach($timeline_data as $key=>$value)
							{{ $key[$value] }}
							  @endforeach
						  </li>
						  <!-- /.timeline-label -->
						  <!-- timeline item -->
						  <li>
							<i class="fas fa-envelope bg-blue"></i>

							<div class="timeline-item">
							  <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

							  <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

							  <div class="timeline-body">
								Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
								weebly ning heekya handango imeem plugg dopplr jibjab, movity
								jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
								quora plaxo ideeli hulu weebly balihoo...
							  </div>
							  <div class="timeline-footer">
								<a class="btn btn-primary btn-xs">Read more</a>
								<a class="btn btn-danger btn-xs">Delete</a>
							  </div>
							</div>
						  </li>
						  <!-- END timeline item -->
						  <!-- timeline item -->
						  <li>
							<i class="fas fa-user bg-aqua"></i>

							<div class="timeline-item">
							  <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

							  <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>
							</div>
						  </li>
						  <!-- END timeline item -->
						  <!-- timeline item -->
						  <li>
							<i class="fas fa-comments bg-yellow"></i>

							<div class="timeline-item">
							  <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

							  <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

							  <div class="timeline-body">
								Take me to your leader!
								Switzerland is small and neutral!
								We are more like Germany, ambitious and misunderstood!
							  </div>
							  <div class="timeline-footer">
								<a class="btn btn-warning btn-flat btn-xs">View comment</a>
							  </div>
							</div>
						  </li>
						  <!-- END timeline item -->
						  <!-- timeline time label -->
						  <li class="time-label">
                  <span class="bg-green">
                    3 Jan. 2014
                  </span>
						  </li>
						  <!-- /.timeline-label -->
						  <!-- timeline item -->
						  <li>
							<i class="fas fa-camera bg-purple"></i>

							<div class="timeline-item">
							  <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

							  <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

							  <div class="timeline-body">
								<img src="http://placehold.it/150x100" alt="..." class="margin">
								<img src="http://placehold.it/150x100" alt="..." class="margin">
								<img src="http://placehold.it/150x100" alt="..." class="margin">
								<img src="http://placehold.it/150x100" alt="..." class="margin">
							  </div>
							</div>
						  </li>
						  <!-- END timeline item -->
						  <!-- timeline item -->
						  <li>
							<i class="fa fa-video-camera bg-maroon"></i>

							<div class="timeline-item">
							  <span class="time"><i class="fa fa-clock-o"></i> 5 days ago</span>

							  <h3 class="timeline-header"><a href="#">Mr. Doe</a> shared a video</h3>

							  <div class="timeline-body">
								<div class="embed-responsive embed-responsive-16by9">
								  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/tMWkeBIohBs" frameborder="0" allowfullscreen=""></iframe>
								</div>
							  </div>
							  <div class="timeline-footer">
								<a href="#" class="btn btn-xs bg-maroon">See comments</a>
							  </div>
							</div>
						  </li>
						  <!-- END timeline item -->
						  <li>
							<i class="fa fa-clock-o bg-gray"></i>
						  </li>
						</ul>
					  </div>
					  <!-- /.col -->
					</div>
					<!-- /.row -->

					<div class="row" style="margin-top: 10px;">
					  <div class="col-md-12">
						<div class="box box-primary">
						  <div class="box-header">
							<h3 class="box-title"><i class="fa fa-code"></i> Timeline Markup</h3>
						  </div>
						  <div class="box-body">
                  <pre style="font-weight: 600;">&lt;ul class="timeline"&gt;

    &lt;!-- timeline time label --&gt;
    &lt;li class="time-label"&gt;
        &lt;span class="bg-red"&gt;
            10 Feb. 2014
        &lt;/span&gt;
    &lt;/li&gt;
    &lt;!-- /.timeline-label --&gt;

    &lt;!-- timeline item --&gt;
    &lt;li&gt;
        &lt;!-- timeline icon --&gt;
        &lt;i class="fa fa-envelope bg-blue"&gt;&lt;/i&gt;
        &lt;div class="timeline-item"&gt;
            &lt;span class="time"&gt;&lt;i class="fa fa-clock-o"&gt;&lt;/i&gt; 12:05&lt;/span&gt;

            &lt;h3 class="timeline-header"&gt;&lt;a href="#"&gt;Support Team&lt;/a&gt; ...&lt;/h3&gt;

            &lt;div class="timeline-body"&gt;
                ...
                Content goes here
            &lt;/div&gt;

            &lt;div class="timeline-footer"&gt;
                &lt;a class="btn btn-primary btn-xs"&gt;...&lt;/a&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/li&gt;
    &lt;!-- END timeline item --&gt;

    ...

&lt;/ul&gt;
                  </pre>
						  </div>
						  <!-- /.box-body -->
						</div>
						<!-- /.box -->
					  </div>
					  <!-- /.col -->
					</div>
					<!-- /.row -->
				  </section>
			</div>

				<div class="clearfix"></div>

		</div>
	</div>
</div>

@include('dashboard.includes.event-modal')
@include('dashboard.includes.document-modal');
@include('dashboard.includes.invoice-modal')
@include('dashboard.includes.case-modal')


<div class="modal fade" id="add-notes-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
				<h3>
          <i class="fas fa-sticky-note"></i> Add Note				
        </h3>
				<div class="clearfix"></div>
				<hr />        
        <form method="POST" action="/dashboard/cases/case/notes/note/add">
          <input type="hidden" name="_token" value="{{ csrf_token() }}"  />
          <input type="hidden" name="case_id" value="{{ $case->id }}" />
          <label>Note</label>
          <textarea name="note" class="form-control"></textarea>
          <button type="submit" class="form-control mt-3 btn btn-primary">
            Submit
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add-hours-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
					<i class="fas fa-tasks"></i> Add hours to case
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form role="form" method="post" action="/dashboard/cases/case/add-hours">
					<input type="hidden" name="case_id" value="{{ $case->id }}"/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-12">
						<label>Amount</label>
						<input type="text" class="form-control" name="hours_worked" />
					</div>
          <div class="col-12">
            <label>Note</label>
            <textarea name="hours_note" class="form-control"></textarea>
          </div>
					<div class="col-12">
						<input type="submit" class="btn btn-primary mt-2 form-control" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="case-edit-modal">
	 <div class="modal-dialog modal-lg">
    <div class="modal-content">
         <div class="modal-body">
           <h3>
             <i class="fas fa-briefcase"></i> Edit case
           </h3>
           <div class="clearfix"></div>
           <hr />
         <form role="form" method="post" action="/dashboard/cases/create">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id" value="{{ $case->id }}">
          <div class="col-sm-6 col-xs-12">
            
 
       
          <label for="inputGroupSelect01">Status</label>
         
            <select class="custom-select" name="status" id="inputGroupSelect01" aria-label="Status" aria-describedby="inputGroup-sizing-sm">
			        @foreach($status_values as $t)
         	      <option value="{{ $t }}" {{ $t == $case->status ? "selected='selected'" : '' }}>{{ ucwords($t) }}</option>
			        @endforeach 
            </select>
         
          </div>       
           
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Case Number</label>
               <input type="text" class="form-control" id="case_number" value="{{ $case->number }}" name="case_number" aria-label="Case Number">
           </div>
           <div class="col-sm-12 col-xs-12">
             
               
                 <label>Name</label>
               <input type="text" class="form-control" id="name" value="{{ $case->name }}" name="name" aria-label="Name">
           </div>   
           <div class="col-sm-12">
             
               
                 <label>Description</label>
               <textarea class="form-control" aria-label="Description" name="description" id="description">{{ $case->description }}</textarea>
           </div>
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Court name</label>
               <input type="text" class="form-control" id="court_name" value="{{ $case->court_name }}" name="court_name" aria-label="Court name">
           </div>
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Opposing Councel</label>
             
               <input type="text" class="form-control" id="opposing_councel" value="{{ $case->opposing_councel }}" name="opposing_councel" aria-label="Opposing Councel">
           </div>              
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Location</label>
               <input type="text" class="form-control" id="location" value="{{ $case->location }}" name="location" aria-label="Location">
           </div>   
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Claim Reference Number</label>
               <input type="text" class="form-control" id="claim_reference_number" value="{{ $case->claim_reference_number }}" name="claim_reference_number" aria-label="Claim reference number">
           </div>   
            <div class="col-sm-6 col-xs-12">
             
               
                 <label>Open date</label>
               <input type="text" class="form-control datepicker" value="{{ \Carbon\Carbon::parse($case->open_date)->format('m/d/Y') }}" id="open_date" name="open_date" aria-label="Open date">
           </div>  
            <div class="col-sm-6 col-xs-12">
             
               
                 <label>Close date</label>
               <input type="text" class="form-control datepicker" id="close_date" value="{{ \Carbon\Carbon::parse($case->close_date)->format('m/d/Y') }}"
  name="close_date" aria-label="Close date">
           </div>
           <div class="col-sm-6 col-xs-12 mt-4">
            
           
                  <label>Statute of Limitations</label>
          	
                <input type="checkbox" {{ !empty($case->statute_of_limitations) ? "checked" : '' }} name="statute_of_limitations" aria-label="Statute of Limitations">
   
    
           </div>
           <div class="col-sm-6 col-xs-12">
             
          
            <label>Rate</label>
          <input type="text" class="form-control" name="billing_rate" value="{{ $case->billing_rate }}" aria-label="Amount (to the nearest dollar)">

           </div>
					 
           <div class="col-sm-6 col-xs-12 mt-4">
             @php
						 $types = ['fixed', 'hourly'];
						 @endphp
            @foreach($types as $type)
						 	<label>{{ ucfirst($type) . " rate" }}</label>
							<input type="radio" name="rate_type" value='{{ $type }}' {{ $case->billing_type === $type ? 'checked=checked' : '' }}" />
						@endforeach


           
            </div>
					 
					 					<div class="col-sm-6 col-xs-12">
						<label>Hours</label>
						<input type="text" class="form-control" name="hours" value="{{ $case->hours }}" aria-label="Hours worked">
					</div>	
					 <div class="col-sm-12 col-xs-12">
					             
                 <button class="btn btn-primary mt-3"><i class="fas fa-check"></i> Submit</button>             
        
					 
					 </div>
          
           <div class="clearfix"></div>
					 </div>
			</div>
		 </div>
	</div>

<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
  @if(!empty($cases))
    var cases = {!! json_encode($cases->toArray()) !!};
    for(var i = 0; i<cases.length; i++){
      cases[i].data = cases[i]['id'];
      cases[i].value = cases[i]['name'];
	  }
 @endif
 
  
  @if(count($clients) > 0)
    var clients = {!! json_encode($clients->toArray()) !!};
  	for(var i = 0; i<clients.length; i++){
      clients[i].data = clients[i]['id'];
      clients[i].value = clients[i]['first_name'] + " " + clients[i]['last_name'];
	  }
  @else
    var clients = [];
  @endif
  
  @if(count($contacts) > 0)
    var contacts = {!! json_encode($contacts->toArray()) !!};	
  	for(var i = 0; i<contacts.length; i++){
		contacts[i].data = contacts[i]['id'];
		contacts[i].value = contacts[i]['first_name'] + " " + contacts[i]['last_name'];
    }
	@else
    var contacts = [];
  @endif
      
	 $('input[name="case_name"]').autocomplete({
    lookup: cases,
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