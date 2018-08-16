@extends('adminlte::page')

@section('content')

<div class="container dashboard leads col-sm-12 offset-sm-2">
  <nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#leads-modal" href="#"><i class="fa fa-plus"></i> <i class="fa fa-user-circle"></i> Add lead</a>
        <a class="nav-item nav-link btn btn-info" href="/dashboard/leads/converted"><i class="fa fa-user"></i> Converted leads</a>

  </nav> 
 					

   <div>
    <div>
        <h1 class="pull-left ml-3 mt-4 mb-2">
          <i class="fas fa-user-circle"></i> {{ Request::segment(3) === 'converted' ? "Converted" : "" }} Leads
        </h1>
				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Leads shows all of your contact information.  Click on a contact to show information.</p>
     </div>
     <div>
      @if (count($leads) < 1)
          <div class="alert alert-warning alert-dismissible in" role="alert">
              No leads for this user! <strong>Add a new lead above!</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</label>
        </button>
      </div>	
      @include('dashboard.includes.alerts')

			@elseif (count($leads) >= 1)
      <table id="main" class="table dataTable table-responsive table-striped table-hover">
                    <thead> 
            <tr class="bg-primary">
                <th>UUID</th>
                <th class="sorting">First name</th>
                <th class="sorting">Last name</th>
                <th>Phone</th>
                <th>Email</th>
          </tr> 
          </thead>  
          <tbody>
    
            @foreach($leads as $lead)
						<tr>
              <td>{{ $lead->lead_uuid }}</td>
              <td>{{ $lead->first_name }}</td>
              <td>{{ $lead->last_name }}</td>
              <td>{{ $lead->phone }}</td>
              <td>{{ $lead->email }}</td>
            </tr>
            @endforeach
            
            @endif			
          </tbody> 
       </table>
     </div>
  </div>  


      

    
@include('dashboard.includes.lead-modal')




       

@endsection