@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-12 offset-sm-2">

    @include('dashboard.type_navigation.contacts')

   <div>
    <div>
        <h1 class="pull-left ml-3 mt-4 mb-2">
         <i class="fas fa-address-card"></i> {{ Request::segment(3) === 'firm' ? 'Firm ' : '' }} {{ ucfirst(Request::segment(2)) }}
        </h1>
				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Contacts shows all of your contact information.  Click on a contact to show information.</p>							 
     </div>
     <div>
      @if (count($contacts) < 1)
          <div class="alert alert-warning alert-dismissible in" role="alert">
              No contacts for this user, yet! <strong>Add a new contact above!</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</label>
        </button>
      </div>	
      @include('dashboard.includes.alerts')

			@elseif (count($contacts) >= 1)      
      <table id="{{ Request::segment(2) }}" class="table dataTable table-responsive table-striped table-hover">
                    <thead> 
            <tr class="bg-primary">           
          @foreach($columns as $column)
            <th class="sorting" scope="col">{{ ucfirst(str_replace("_", " ", $column)) }}</th>
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