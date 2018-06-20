@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-12 offset-sm-2">
    <nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#client-modal" href="#"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add client</a>
		<a class="nav-item nav-link btn btn-info" href="/dashboard/client/mine"><i class="fa fa-users"></i> My clients</a>
  </nav> 


   <div>
    <div>
        <h1 class="pull-left ml-3 mt-4 mb-2">
         <i class="fas fa-users fa-fw fa-lg"></i> Clients
        </h1>
				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Clients shows all of your client information regarding all cases.  Click on a client to show information.</p>							
 
            
     </div>
     				 	@if (count($contacts) < 1)
           <div class="alert alert-warning alert-dismissible in" role="alert">
						No clients for this user, yet! <strong>Add a new client above!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</label>
            </button>
           </div>
          							@include('dashboard.includes.alerts')

		  		  @elseif (count($contacts) >= 1) 
     <div>
        <table id="main" class="table dataTable table-responsive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
                    <thead> 
            <tr>           
          @foreach($columns as $column)

            <th class="sorting bg-primary" scope="col">{{ ucfirst(str_replace("_", " ", $column)) }}</th>     
            
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

@include('dashboard.includes.client-modal')

@endsection