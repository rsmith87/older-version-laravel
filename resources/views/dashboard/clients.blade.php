@extends('layouts.dashboard')

@section('content')

<div class="container dashboard col-sm-10 col-12 offset-sm-2">
    <nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contacts-modal" href="#"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add client</a>
		<a class="nav-item nav-link btn btn-info" href="/dashboard/client/mine"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> My clients</a>
  </nav> 
	
	@include('dashboard.includes.alerts')
	
   <div class="panel panel-default">
    <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mb-5">
         <i class="fas fa-users fa-fw fa-lg"></i> Clients
        </h1>
     </div>
     <div class="panel-body">
        <table class="table table-responsive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
         <thead> 
            <tr>           
          @foreach($columns as $column)

              <th scope="col">{{ $column }}</th>
          
          @endforeach
                         </tr> 
          </thead>  
          <tbody>
            @if (count($contacts) < 1)
            <div class="alert alert-warning alert-dismissible" role="alert">
              No contacts for this user, yet! <strong>Add a new contact above!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>
            @elseif (count($contacts) >= 1)    
             <tr>
            @foreach($contacts as $contact)
              @foreach ($columns as $column)
              <td>{{ $contact->$column }}</td>
              @endforeach
            </tr>
            @endforeach
             </tr>
            @endif
          </tbody> 
       </table>
     </div>
  </div>  
</div>

@foreach($other_data as $contact)

       <div class="modal fade" id="contact-modal-{{ $contact->id }}">
           <div class="modal-dialog">
    <div class="modal-content">
         <div class="modal-body">
           <h3>
             <i class="fas fa-users fa-fw fa-lg"></i> Edit a Client
           </h3>
           <div class="clearfix"></div>
           <hr />
             <form method="post" action="/dashboard/clients/add">
           <input type="hidden" name="case_id" value="{{ $contact->case_id }}">
							 <input type="hidden" name="id" value="{{ $contact->id }}">
               <input type="hidden" name="is_client" value="1">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
               

           <div class="col-sm-6 col-xs-12">
                 <label>First name</label>
               <input type="text" class="form-control" value="{{ $contact->first_name }}" name="first_name" aria-label="First Name">
     
           </div>  
           <div class="col-sm-6 col-xs-12">

                 <label>Last name</label>
         
               <input type="text" class="form-control" value="{{ $contact->last_name }}" name="last_name" aria-label="Last Name">
             
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
                 <label>Address</label>
              
               <input type="text" class="form-control" value="{{ $contact->address }}" name="address" aria-label="Address">
           
           </div> 
           <div class="col-sm-12 col-xs-12">

              <label>Case</label>

							 <input type="hidden" name="case_id" value="{{ $contact->case_id }}" />
							@if($contact->case_id)
							 @foreach ($cases as $key => $case)
								@if($contact->case_id == $case->id)
							 		<input type="text" name="case_name" class="form-control" value="{{ $case->name }}" />						 
							   @endif
							 @endforeach
							@else
							  <input type="text" name="case_name" class="form-control" />		
							 @endif

               

           </div>   

      
         <button class="btn btn-primary mb-4 mt-4">
           <i class="fas fa-check"></i> Submit
         </button>
       
                            </form>
					  <h3>
             <i class="fas fa-user"></i>Documents
           </h3>
           
           <table class="table table-responsive table-striped table-{{ $table_color }} table-{{ $table_size }}">
          <thead>

          </thead> 
          <tbody> 
            @foreach($contact->Documents_Client as $document)
            <tr>
            <td>{{ $document->name }}</td>
            <td>{{ $document->description }}</td>
            <td><a class="btn btn-primary btn-sm float-right" target="_blank" href="http://s3.amazonaws.com/legaleeze{{ $document->path }}">Download</a></td>
            </tr>
            @endforeach
          </tbody>
           </table>           


       </div>
			
			         
             </div>
       </div>

</div>

@endforeach


       <div class="modal fade" id="contacts-modal">
           <div class="modal-dialog">
    <div class="modal-content">
         <div class="modal-body">
					  <form method="post" action="/dashboard/contacts/add">
      
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="is_client" value="1" />
          <input type="hidden" name="type" value="client">
           <h3>
             <i class="fas fa-address-card"></i> Add a Client
           </h3>
           <div class="clearfix"></div>
           <hr />
         
                  <div class="col-sm-6 col-xs-12">

                 <label>First name</label>
             
               <input type="text" class="form-control" name="first_name" aria-label="First Name">
       
           </div>  
           <div class="col-sm-6 col-xs-12">

                 <label>Last name</label>
              
               <input type="text" class="form-control" name="last_name" aria-label="Last Name">
            
           </div>                
           <div class="col-sm-6 col-xs-12">

                 <label>Company</label>
             
               <input type="text" class="form-control" name="company" aria-label="Company">
           </div> 
           <div class="col-sm-6 col-xs-12">

                 <label>Company title</label>
            
               <input type="text" class="form-control" name="company_title" aria-label="Company title">
            
           </div> 
           <div class="col-sm-6 col-xs-12">
     
                 <label>Phone</label>
            
               <input type="text" class="form-control" id="phone" name="phone" aria-label="Phone">
         
           </div>     
           <div class="col-sm-6 col-xs-12">

                 <label>Email</label>
           
               <input type="text" class="form-control" name="email" aria-label="Email">
            
           </div> 
           <div class="col-sm-6 col-xs-12">

                 <label>Address</label>
   
               <input type="text" class="form-control" name="address" aria-label="Address">
         
           </div> 
           <div class="col-sm-12 col-xs-12">

                 <label>Case</label>
							 
							 <input type="hidden" name="case_id" />
               <input type="text" name="case_name" class="form-control" />
  
           
           </div>   
                

         <button class="btn btn-primary mt-2 mb-1">
           <i class="fas fa-check"></i> Submit
         </button>
					 </form>
       </div>
             </div>
       </div>

</div>
           </div>
             </form>

<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
var cases = {!! json_encode($cases->toArray()) !!};

  for(var i = 0; i<cases.length; i++){
	
	  cases[i].data = cases[i]['id'];
	  cases[i].value = cases[i]['name'];

		//console.log(cases[i])
}
	
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
	

</script>
       

@endsection