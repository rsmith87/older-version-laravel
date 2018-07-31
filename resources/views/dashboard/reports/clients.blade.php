@extends('adminlte::page')

@section('content')

<div class="container dashboard reports charts col-sm-12 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports"><i class="fas fa-user"></i> Clients</a>
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports/cases"><i class="fas fa-briefcase"></i> Cases</a>
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports/payments"><i class="fas fa-dollar-sign"></i> Payments</a>
  </nav>
  
  @include('dashboard.includes.alerts')

   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mt-4 mb-2">
          <i class="fas fa-chart-line"></i> Clients
        </h1>
     </div>
     <div class="panel-body">
       <div class="col-sm-12 col-12">
         	<table class="table table-responsive table-resposive table-striped table-{{ $table_color }} table-{{ $table_size }}">
			  <tr>
				<th scope="col">First name</th>
				<th scope="col">Last name</th>
				<th scope="col">Relationship</th>
				<th scope="col">Company</th>
				<th scope="col">Phone</th>
				<th scope="col">Email</th>
				<th scope="col">Address</th>
				<th scope="col">City</th>
				<th scope="col">State</th>
				<th scope="col">Zip</th>
				<th scope="col">Case</th>
				<th scope="col">Created</th>
			  </tr>
			  </thead>
			  <tbody>
			  @foreach($clients as $client)
				<tr>
				  <td>{{ $client->first_name }}</td>
				  <td>{{ $client->last_name }}</td>
				  <td>{{ $client->relationship }}</td>
				  <td>{{ $client->company }}</td>
				  <td>{{ $client->phone }}</td>
				  <td>{{ $client->email }}</td>
				  <td>{{ $client->address_1 }}</td>
				  <td>{{ $client->state }}</td>
				  <td>{{ $client->zip}}</td>
				  <td></td>
				  <td>{{ \Carbon\Carbon::parse($client->created_at)->format('m/d/Y') }}</td>

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