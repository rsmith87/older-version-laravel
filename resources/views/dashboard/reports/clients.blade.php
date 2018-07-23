@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-10 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports"><i class="fas fa-user"></i> Clients by month</a>
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports/cases"><i class="fas fa-briefcase"></i> Cases by month</a>
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports/payments"><i class="fas fa-dollar-sign"></i> Payments by month</a> 
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports/hours"><i class="fas fa-clock"></i> Hours worked by week</a>        
  </nav>  
  
  @include('dashboard.includes.alerts')

   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mt-4 mb-2">
          <i class="fas fa-chart-line"></i> New Clients
        </h1>
     </div>
     <div class="panel-body">
        <div class="col-sm-12 col-12">
          {!! $chart->container() !!}
       </div>
       <div class="col-sm-12 col-12">
         	<table class="table table-responsive table-resposive table-striped table-{{ $table_color }} table-{{ $table_size }}">
					<thead>
						<tr>           
								<th scope="col">ID</th>
								<th scope="col">Name</th>
						</tr> 
					</thead> 
					<tbody>             
            @foreach($clients_full as $client)
            <tr>
              <td>{{ $client->client_uuid }}</td>
              <td>{{ $client->first_name }} {{ $client->last_name }}</td>
            </tr>
            @endforeach

            
					</tbody> 
				</table>
       </div>
     </div>
  </div>   
  </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js" charset="utf-8"></script>
{!! $chart->script() !!}
@endsection