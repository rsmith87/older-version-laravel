@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-12 offset-sm-2">
  <nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contacts-modal" href="#"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add contact</a>
		<a class="nav-item nav-link btn btn-info"  href="/dashboard/contacts/mine"><i class="fa fa-users"></i> My contacts</a>
	
  </nav> 
						@include('dashboard.includes.alerts')
 					@if (count($contacts) < 1)
           <div class="alert alert-warning alert-dismissible in" role="alert">
						No contacts for this user, yet! <strong>Add a new contact above!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</label>
            </button>
           </div>	
			  		  @elseif (count($contacts) >= 1)  

   <div class="panel panel-primary">
    <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mt-4 mb-2">
         <i class="fas fa-address-card"></i> Contacts
        </h1>
				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Contacts shows all of your client information.  Click on a contact to show information.</p>							 
     </div>
     <div class="panel-body">
        <table id="main" class="table dataTable table-responsive table-striped table-hover">
                    <thead> 
            <tr>           
          @foreach($columns as $column)

              <th class="sorting" scope="col">{{ $column }}</th>
          
          @endforeach

          </tr> 
          </thead>  
          <tbody>
    
            @foreach($contacts as $contact)
						<tr>
              @foreach ($columns as $column)
              <td>{{ ucfirst($contact->$column) }}</td>
              @endforeach
            </tr>
            @endforeach
            
            @endif			
          </tbody> 
       </table>
     </div>
  </div>  


      

    
@include('dashboard.includes.contact-modal')
@include('dashboard.includes.client-modal')




       

@endsection