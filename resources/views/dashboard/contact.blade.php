@extends('layouts.dashboard')

@section('content')

<div class="container dashboard contact col-sm-10 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contacts-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Edit {{ Request::segment(3) }}</a>	
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contact-relate-case-modal" href="#"><i class="fa fa-user"></i> <i class="fa fa-plus"></i> <i class="fa fa-briefcase"></i> Relate  {{ Request::segment(3) }} to case</a>				
	</nav>  	

	
   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-4 mt-4 mb-2"> 
          <i class="fas fa-briefcase"></i> {{ ucfirst(Request::segment(3)) }} 
        </h1>
				@include('dashboard.includes.alerts')
				
     </div>
     <div class="panel-body">
			 <div class="container">
			
				 <div class="col-sm-6 col-12">
			 <label>Name</label>
			 <p>{{ $contact->first_name }} {{ $contact->last_name }}</p>
       <label>Relationship</label>
       <p>
         {{ !empty($contact->relationship) ? ucfirst($contact->relationship) : "Not set" }}
           </p>
			 <label>Address</label>
           <p><a href="#" class="mapThis" place="{{ $contact->address_1 }} {{ $contact->address_2 }} {{ $contact->city }} {{ $contact->state }} {{ $contact->zip }}" zoom="16">{{ $contact->address_1 }}
					 {{ $contact->address_2 }}<br />
					 {{ $contact->city }}<br />
					 {{ $contact->state }}<br />
             {{ $contact->zip }}</a></p>


				 </div>
				 <div class="col-sm-6 col-12">	
					 <label>Phone</label>
           <p><a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a></p>         
			 <label>Company</label>
			 <p>{{ $contact->company }}</p>					 
			 <label>Company title</label>
			 <p>{{ $contact->company_title }}</p>

					 <label>Email</label>
           <p><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
				 </div>
		<div class="clearfix"></div>
     
    @if(count($notes) > 0)
      <h3>
        <i class="fas fa-sticky-note"></i> Client notes
      </h3>
      <div class="clearfix"></div>
      <div class="mb-3" style="overflow:hidden;">
       
        <div class="col-sm-6 col-12">
          <label>Note</label>
           @foreach($notes as $note)
          <p>
            {{ $note->note }}
          </p>
          @endforeach
      </div>
      <div class="col-sm-6 col-12">
        <label>Created</label>
        @foreach($notes as $note)
        <p>
        {{ $note->created_at }}
        </p>
        @endforeach
      </div>
      </div>
      @endif
 
          <div class="clearfix"></div><h3>
      <i class="fas fa-sticky-note"></i> Add Note
      </h3>
        <form method="POST" action="/dashboard/contacts/contact/notes/note/add">
          <input type="hidden" name="_token" value="{{ csrf_token() }}"  />
          <input type="hidden" name="contact_client_id" value="{{ $contact->id }}" />
          <textarea name="note" class="form-control"></textarea>
          <button type="submit" class="form-control mt-3 btn btn-primary">
            Submit
          </button>
        </form>
         <div class="clearfix"></div>
				 @if(count($contact->Documentsclients) > 0)

           <h3 class="mt-5 mb-2">
             <i class="fas fa-user"></i>Documents
           </h3>
           
           <table id="documents" class="table table-{{ $table_size }} table-hover table-responsive table-striped table-{{ $table_color }}">
          <thead>
            <tr> 
              <th>ID</th>
              <th>File name</th> 
              <th>File description</th>
            </tr> 
          </thead> 
          <tbody> 
						@if($contact->is_client === 1)
            	@foreach($contact->Documentsclients as $document)
								<tr>
                  <td>{{ $document->id }}</td>
									<td>{{ $document->name }}</td>
									<td>{{ $document->description }}</td>
								</tr>
            	@endforeach						
						@else
						  @foreach($contact->Documents as $document)
								<tr>
                  <td>{{ $document->id }}</td>
									<td>{{ $document->name }}</td>
									<td>{{ $document->description }}</td>
									<td><button class="btn btn-primary btn-sm" href="{{ $document->location }}">Download</button></td>
								</tr>
            	@endforeach							
						@endif
            
          </tbody>
          </table>           

				@endif 

				@if(count($contact->Tasks) > 0)

				 <div class="clearfix"></div>
           <h3 class="mt-5 mb-2">
             <i class="fas fa-user"></i>Tasks
           </h3>
          <table id="tasks" class="table table-{{ $table_size }} table-hover table-responsive table-striped table-{{ $table_color }}">
          <thead>
            <tr> 
              <th>Id</th>
              <th>Name</th> 
              <th>Description</th>
            </tr> 
          </thead> 
          <tbody> 
				  	@foreach($contact->Tasks as $task)
				 			<tr>
                <td>{{ $task->id }}</td>
								<td>{{ $task->task_name }}</td>
								<td>{{ $task->task_description }}</td>
						</tr>
				 		@endforeach
						 </tbody>
				 </table>
				 @endif
			 </div>
     </div>
  </div>   


<div class="modal fade" id="contacts-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
				<i class="fas fa-address-card"></i> Edit a contact
				</h3>
				<div class="clearfix"></div>
				<hr />
					<form method="post" action="/dashboard/contacts/add">
						<input type="hidden" name="id" value="{{ $contact->id }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="is_client" value="{{ $is_client }}" />


						<div class="col-sm-6 col-xs-12">
							<label>First name</label>
							<input type="text" class="form-control" value="{{ $contact->first_name }}" name="first_name" aria-label="First Name">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Last name</label>
							<input type="text" class="form-control" value="{{ $contact->last_name }}" name="last_name" aria-label="Last Name">
						</div> 
          
						<div class="col-sm-6 col-xs-12">
							<label>Relationship</label>
                @php
                $relationship_values = [
               'select one', 'grandfather', 'grandmother', 'father', 'mother', 'brother', 'sister', 'uncle', 'aunt', 'husband', 'wife', 'boss', 'coworker', 'friend'
                ]
                @endphp
                  <select class="form-control" name="relationship" id="inputGroupSelect01" aria-label="Relatinoship">
                  @foreach($relationship_values as $t)
                    <option value="{{ $t }}" {{ $t == $contact->relationship ? "selected='selected'" : '' }}>{{ ucwords($t) }}</option>
                  @endforeach 
                </select>                
						</div>            
						
						<div class="col-sm-6 col-xs-12">
							<label>Company</label>
							<input type="text" class="form-control"  value="{{ $contact->company }}" name="company" aria-label="Company">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Company title</label>
							<input type="text" class="form-control" value="{{ $contact->company_title }}" name="company_title" aria-label="Company title">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Phone</label>
							<input type="text" class="form-control" id="phone" value="{{ $contact->phone }}" name="phone" aria-label="Phone">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Email</label>
							<input type="text" class="form-control" value="{{ $contact->email }}" name="email" aria-label="Email">
						</div> 
						
						<div class="col-sm-6 col-xs-12">
							<label>Address 1</label>
							<input type="text" class="form-control" value="{{ $contact->address_1 }}" name="address_1" aria-label="Address">
						</div> 

					<div class="col-sm-6 col-xs-12">
						<label>Address 2</label>
						<input type="text" class="form-control" name="address_2" value="{{ $contact->address_2 }}" aria-label="Address">
					</div> 	
					
					<div class="col-sm-6 col-xs-12">
						<label>City</label>
						<input type="text" class="form-control" name="city" value="{{ $contact->city }}" aria-label="Address">
					</div> 	 
					
						<div class="col-sm-6 col-xs-12">
						<label>State</label>
						<input type="text" class="form-control" name="state" value="{{ $contact->state }}" aria-label="Address">
					</div>
					
					<div class="col-sm-6 col-xs-12">
						<label>Zip</label>
						<input type="text" class="form-control" name="zip" value="{{ $contact->zip }}" aria-label="Address">
					</div> 	 
						<div class="clearfix"></div>
						<hr />
						<div class="col-sm-12 col-xs-12">
							<label>Case</label>
							<input type="hidden" name="case_id" value="{{ !empty($contact->case_id) ? $contact->case_id : '' }}" />		
							<input type="text" name="case_name" value="{{ $contact->case_id != 0 || !empty($array_cases[$contact->case_id]) ? $array_cases[$contact->case_id] : '' }}" class="form-control mb-4" />
						</div>
						
						<div class="col-sm-12">
							<button class="btn btn-primary mt-1 mb-5">
								<i class="fas fa-check"></i> Submit
							</button
						</div>

					</form>
			</div>
		</div>
	</div>
</div>

<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
var cases = {!! json_encode($cases->toArray()) !!};

  for(var i = 0; i<cases.length; i++){
	
	  cases[i].data = cases[i]['id'];
	  cases[i].value = cases[i]['name'];

		//console.log(cases[i])
}
	
	$('input[name="case_name"]').on('keyup', function(){
		var $this = $(this);
		if($this.text() == ""){
			$this.prev().attr('value', 0);
		}
		else
			{
				$this.prev().attr('value')
			}
	});
	
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
var cursorX;
var cursorY;
if (window.Event) {
  document.captureEvents(Event.MOUSEMOVE);
}
document.onmousemove = getCursorXY;
$(".mapThis").each(function() {
  var dPlace = $(this).attr("place");
  var dZoom = $(this).attr("zoom");
  var dText = $(this).html();
  $(this).html('<a onmouseover="mapThis.show(this);" style="text-decoration:none; href="http://maps.google.com/maps?q=' + dPlace + '&z=' + dZoom + '">' + dText + '</a>');
 });
 
var mapThis=function(){
var tt;
var errorBox;
return{
  show:function(v){
   if (tt == null) {
   var pNode = v.parentNode;
   pPlace = $(pNode).attr("place");
   pZoom = parseInt($(pNode).attr("zoom"));
   pText = $(v).html();
   tt = document.createElement('div');
   $(tt).html('<a href="http://maps.google.com/maps?q=' + pPlace + '&z='+pZoom+'" target="new"><img border=0 src="http://maps.google.com/maps/api/staticmap?center=' + pPlace + '&zoom=' + pZoom + '&size=200x200&sensor=false&key=AIzaSyBHrSRGRuZmx2mHt7xnTy5aMKL1jYhFMqE&format=png&markers=color:blue|' + pPlace + '"></a>');
   tt.addEventListener('mouseover', function() { mapHover = 1; }, true);
   tt.addEventListener('mouseout', function() { mapHover = 0; }, true);
   tt.addEventListener('mouseout', mapThis.hide, true);
   document.body.appendChild(tt);    
}
fromleft = cursorX;
fromtop = cursorY;
fromleft = fromleft - 25;
fromtop = fromtop - 25;
tt.style.cssText = "position:absolute; left:" + fromleft + "px; top:" + fromtop + "px; z-index:999; display:block; padding:1px; margin-left:5px; width:200px; -moz-box-shadow:0 1px 10px rgba(0, 0, 0, 0.5);";   
tt.style.display = 'block';
},
hide:function(){
tt.style.display = 'none';
tt = null;
}
 };
}();
function getCursorXY(e) {
cursorX = (window.Event) ? e.pageX : event.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
cursorY = (window.Event) ? e.pageY : event.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
 }
	
</script>
       



@endsection