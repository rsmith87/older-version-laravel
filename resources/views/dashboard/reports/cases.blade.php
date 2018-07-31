@extends('adminlte::page')

@section('content')

<div class="container dashboard reports charts col-sm-12 col-xs-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports"><i class="fas fa-user"></i> Clients</a>
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports/cases"><i class="fas fa-briefcase"></i> Cases </a>
    <a class="nav-item nav-link btn btn-info" href="/dashboard/reports/payments"><i class="fas fa-dollar-sign"></i> Payments</a>
  </nav>  

  @include('dashboard.includes.alerts')

  <div class="panel panel-default">
	<div class="panel-heading" style="overflow:hidden;">
	  <h1 class="pull-left ml-3 mt-4 mb-2">
		<i class="fas fa-chart-line"></i> Cases
	  </h1>
	</div>
	<div class="panel-body">
	  <div class="col-sm-12 col-12">

	  </div>
	  <div class="col-sm-12 col-12">
        <table class="table table-responsive table-responsive table-striped">
		  <thead>
		  <tr>
			<th scope="col">Status</th>
			<th scope="col">Type</th>
			<th scope="col">Number</th>
			<th scope="col">Name</th>
			<th scope="col">Court Name</th>
			<th scope="col">Opposing Counsel</th>
			<th scope="col">Location</th>
			<th scope="col">Open date</th>
			<th scope="col">Close date</th>
			<th scope="col">S.O.L</th>
			<th scope="col">Billing Type</th>
			<th scope="col">Billing Rate</th>
		  </tr>
		  </thead>
		  <tbody>
		  @foreach($cases as $case)
            <tr>
			  <td>{{ $case->status }}</td>
			  <td>{{ $case->type }}</td>
			  <td>{{ $case->number }}</td>
              <td>{{ $case->name }}</td>
			  <td>{{ $case->court_name }}</td>
			  <td>{{ $case->opposing_counsel }}</td>
			  <td>{{ $case->location }}</td>
			  <td>{{ \Carbon\Carbon::parse($case->open_date)->format('m/d/Y') }}</td>
			  <td>{{ \Carbon\Carbon::parse($case->close_date)->format('m/d/Y') }}</td>
			  <td>{{ isset($case->statute_of_limitations) ? "Yes" : "No" }}</td>
			  <td>{{ $case->billing_type }}</td>
			  <td>{{ $case->billing_rate }}</td>
			</tr>
            @endforeach


		  </tbody>
		</table>
	  </div>
	</div>
  </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js" charset="utf-8"></script>
@endsection