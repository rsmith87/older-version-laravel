@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-12 offset-sm-2">
    <nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#client-modal" href="#"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add client</a>
		<a class="nav-item nav-link btn btn-info" href="/dashboard/client/mine"><i class="fa fa-users"></i> My clients</a>
  </nav> 
	
	
   <div class="panel panel-primary">
    <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mt-4 mb-2">
         <i class="fas fa-users fa-fw fa-lg"></i> Clients
        </h1>
				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Clients shows all of your client information regarding all cases.  Click on a client to show information.</p>							
						@include('dashboard.includes.alerts')
			 	@if (count($contacts) < 1)
           <div class="alert alert-warning alert-dismissible in" role="alert">
						No contacts for this user, yet! <strong>Add a new contact above!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</label>
            </button>
           </div>
		  		  @elseif (count($contacts) >= 1)  
            
     </div>
     <div class="panel-body">
        <table id="main" class="table table-responsive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
                    <thead> 
            <tr>           
          @foreach($columns as $column)

              <th scope="col">{{ $column }}</th>
          
          @endforeach
          </tr> 
          </thead>  
          <tbody>
           
             
            @foreach($contacts as $contact)
						<tr>
              @foreach ($columns as $column)
              <td>{{ $contact->$column }}</td>
              @endforeach
            </tr>
            @endforeach
            
            @endif			
          </tbody> 
       </table>
     </div>
  </div>  
</div>

       

@endsection