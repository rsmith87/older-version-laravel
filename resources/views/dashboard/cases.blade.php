@extends('layouts.dashboard')

@section('content')

<div class="container dashboard cases col-sm-10 col-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Add case</a>
    <a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-briefcase"></i> My Cases</a>
  </nav>  	

	@include('dashboard.includes.alerts')
	
   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-4 mb-5">
          <i class="fas fa-briefcase"></i> Cases
        </h1>
     </div>
     <div class="panel-body">
       @if (count($cases) === 0)
       <div class="alert alert-warning alert-dismissible fade in" role="alert">
        No cases for this user, yet! <strong>Add a new case above!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
       @endif
       @if ($firm_id === "")
       <div class="alert alert-danger alert-dismissible fade in" role="alert">
        You have not created or assigned yourself to a firm!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
       @endif
       
       @if (count($cases) > 0)
        <table class="table table-responsive table-resposive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
          <thead> 
            <tr>           
          @foreach($columns as $column)

              <th scope="col">{{ ucfirst($column) }}</th>
          
          @endforeach
                         </tr> 
          </thead> 
          <tbody> 
           @foreach($cases as $case)
            <tr>
             @foreach ($columns as $column)

              @if ($column === 'statute_of_limitations' and $case->$column != "")
               <td>{{ \Carbon\Carbon::parse($case->column)->format('m/d/Y') }}</td>
              @else
               <td>{{ $case->$column }}</td>
              @endif

             @endforeach
            </tr>
           @endforeach
           
          </tbody> 
       </table>
       @endif
       <div class="add-task add-case hide">
         
         <!--
      Schema::create('case', function (Blueprint $table) {
        $table->increments('id')->primary();
        $table->string('status');        
        $table->string('number');
        $table->string('name');
        $table->string('description');        
        $table->string('court_name')
        $table->string('opposing_councel');
        $table->string('claim_reference_number');
        $table->string('location');
        $table->dateTime('created_at');
        $table->dateTime('open_date');
        $table->dateTime('close_date');
        $table->dateTime('statue_of_limitations')
        $table->json('contacts');
        $table->boolean('is_billable');
        $table->string('billing_type');
        $table->foreign('lawyer_id')->references('id')->on('users');
-->
     
                </div>
     </div>
  </div>   


 

</div>
<div class="modal fade" id="case-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
         <div class="modal-body">
           <h3>
             <i class="fas fa-briefcase"></i>Add new case
           </h3>
           <div class="clearfix"></div>
           <hr />
         <form role="form" method="post" action="/dashboard/cases/create">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="col-sm-6 col-xs-12">
            
 
   
   
              <label>Status</label>
          
            <select class="custom-select" id="inputGroupSelect01" name="status" aria-label="Status" aria-describedby="inputGroup-sizing-sm">
              <option value="" selected>Choose...</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
           
          </div>       
           
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Case Number</label>
               
               <input type="text" class="form-control" id="case_number" name="case_number" aria-label="Case Number">
             
           </div>
           <div class="col-sm-12 col-xs-12">
             
               
                 <label>Name</label>
             
               <input type="text" class="form-control" id="name" name="name" aria-label="Name">
           
           </div>   
           <div class="col-sm-12">
             
               
                 <label>Description</label>
               
               <textarea class="form-control" aria-label="Description" name="description" id="description"></textarea>
                     
           </div>
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Court name</label>
              
               <input type="text" class="form-control" id="court_name" name="court_name" aria-label="Court name">
         
           </div>
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Opposing Councel</label>
             
               <input type="text" class="form-control" id="opposing_councel" name="opposing_councel" aria-label="Opposing Councel">
           </div>              
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Location</label>
               
               <input type="text" class="form-control" id="location" name="location" aria-label="Location">
           </div>   
           <div class="col-sm-6 col-xs-12">
             
               
                 <label>Claim Reference Number</label>
               <input type="text" class="form-control" id="claim_reference_number" name="claim_reference_number" aria-label="Claim reference number">
           </div>   
            <div class="col-sm-6 col-xs-12">
             
               
                 <label>Open date</label>
               <input type="text" class="form-control datepicker" data-toggle="datepicker" id="open_date" name="open_date" aria-label="Open date">
           </div>  
            <div class="col-sm-6 col-xs-12">
             
               
							<label>Close date</label>
               <input type="text" class="form-control datepicker" data-toggle="datepicker" id="close_date" name="close_date" aria-label="Close date">
           </div>
           <div class="col-sm-6 col-xs-12">
            
           
             <label>Statute of Limitations</span>
                <input type="checkbox" name="statute_of_limitations" aria-label="Statute of Limitations">

          
           </div>
           <div class="col-sm-6 col-xs-12">
             

           
          
           
       
					  <label>Rate</label>
          <input type="text" class="form-control" name="rate" aria-label="Amount (to the nearest dollar)">

         
           </div>
           <div class="col-sm-6 col-xs-12">
             
          
						 <label>Fixed rate</label>
              <input type="radio" name="rate" value="fixed" aria-label="Fixed rate">
          

         
           </div>
                <div class="col-sm-6 col-xs-12">
       
             
            

						 <label>Hourly rate</label>
              <input type="radio" name="rate" value="hourly" aria-label="Hourly rate">

         
        
             </div>
          
          

           <div class="clearfix"></div>
           <hr />
           <button class="btn btn-warning non-full-width pull-left" id="show-contact"><i class="fas fa-address-card"></i></button>

           <h3>
             <i class="fas fa-user"></i>Contacts
           </h3>
           <table class="table table-responsive table-striped table-responsive table-{{ $table_color }}">
          <thead>
            <tr> 
              <th>Name</th> 
              <th>Phone Number</th>
              <th>Email</th>
            </tr> 
          </thead> 
          <tbody> 
              
           </tbody>
           </table>

        <div class="clearfix"></div>
           <div class="add-contact">
             
             
               <div class="form-group">
            
             <label for="contact_name">Name</label>
             <input type="text" class="form-control" name="contact_name" id="contact-name" placeholder="Contact Name" />
     </div>
     <div class="form-group">
       

             <label for="contact_relationship">Relationship</label>
             <input type="text" class="form-control" name="contact_relationship" id="contact-relationship" placeholder="Contact Relationship" />
             </div>
                  <div class="form-group">
       

             <label for="phone_number">Phone Number</label>
             <input type="text" class="form-control" name="phone_number" id="phone-number" placeholder="Phone Number" />
             </div>
             <div class="form-group">
                 <button class="btn btn-primary"><i class="fas fa-check"></i> Submit</button>             
             </div>

           </div>
          <div class="clearfix"></div>
                      <button class="btn btn-warning non-full-width pull-left" id="show-contact"><i class="fas fa-file"></i></button>
           <h3>
             <i class="fas fa-user"></i>Documents
           </h3>
           
           <table class="table table-responsive table-striped table-{{ $table_color }}">
          <thead>
            <tr> 
              <th>File name</th> 
              <th>Download link</th>

            </tr> 
          </thead> 
          <tbody> 
              
           </tbody>
           </table>           
           <hr />


           <button class="btn btn-primary"><i class="fas fa-check"></i> Submit</button>
       </form>
           

   
      
     
         </div>
         </div>
	</div>
</div>
         
         @foreach ($all_case_data as $case)


<div class="modal fade" id="case-modal-{{ $case->id }}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
         <div class="modal-body">
           <h3>
             <i class="fas fa-briefcase"></i>Add new case
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
           <div class="col-sm-6 col-xs-12">
            
           
                  <label>Statute of Limitations</label>
          	
                <input type="checkbox" {{ !empty($case->statute_of_limitations) ? "checked" : '' }} name="statute_of_limitations" aria-label="Statute of Limitations">
   
    
           </div>
           <div class="col-sm-6 col-xs-12">
             

           
          
            <label>Rate</label>
          <input type="text" class="form-control" name="rate" value="{{ $case->billing_rate }}" aria-label="Amount (to the nearest dollar)">

           </div>
           <div class="col-sm-6 col-xs-12">
             
             <label>Fixed rate</label>
            
              <input type="radio" name="rate_type" aria-label="Fixed rate">
            <label>Hourly rate</label>

                    <input type="radio" aria-label="Hourly rate">

           
            </div>
					 <div class="col-sm-12 col-xs-12">
					             
                 <button class="btn btn-primary"><i class="fas fa-check"></i> Submit</button>             
        
					 
					 </div>
          
           <div class="clearfix"></div>
           <hr />
            <h3>
             <i class="fas fa-user"></i>Contacts
           </h3>

           <table class="table table-responsive table-striped table-{{ $table_color }}">
          <thead>
            <tr> 
							<th>Id</th>
              <th>Name</th> 
              <th>Phone Number</th>
              <th>Email</th>
            </tr> 
          </thead> 
          <tbody> 
            @foreach($case->Contacts as $contact)
            <tr>
							<td>ID</td>
              <td>{{ $contact->first_name }} {{ $contact->last_name }}</td>
              <td>{{ $contact->phone }}</td>
              <td>{{ $contact->email }}</td>
            </tr>
            @endforeach
          </tbody>
           </table>

        <div class="clearfix"></div>
           <div class="add-contact">
             
             
               <div class="form-group">
            
             <label for="contact_name">Name</label>
             <input type="text" class="form-control" name="contact_name" id="contact-name" placeholder="Contact Name" />
     </div>
     <div class="form-group">
       

             <label for="contact_relationship">Relationship</label>
             <input type="text" class="form-control" name="contact_relationship" id="contact-relationship" placeholder="Contact Relationship" />
             </div>
                  <div class="form-group">
       

             <label for="phone_number">Phone Number</label>
             <input type="text" class="form-control" name="phone_number" id="phone-number" placeholder="Phone Number" />
             </div>


           </div>
          <div class="clearfix"></div>
           <h3>
             <i class="fas fa-user"></i>Documents
           </h3>
           
           <table class="table table-responsive table-striped table-{{ $table_color }}">
          <thead>
            <tr> 
              <th>File name</th> 
              <th>File description</th>
              <th>Download link</th>

            </tr> 
          </thead> 
          <tbody> 
            @foreach($case->Documents as $document)
            <tr>
            <td>{{ $document->name }}</td>
            <td>{{ $document->description }}</td>
            <td><button class="btn btn-primary btn-sm" href="{{ $document->location }}">Download</button></td>
            </tr>
            @endforeach
          </tbody>
           </table>           
           <hr />


       </form>
           

       </div>
      
     </div>
	</div>
		</div>
   


       @endforeach

@endsection