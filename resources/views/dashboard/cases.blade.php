@extends('layouts.dashboard')

@section('content')

<div class="container dashboard cases col-sm-10 col-12 offset-sm-2">
	
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Add case</a>
		<a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-briefcase"></i> My Cases</a>
	</nav>  	
	
	<div class="panel panel-default">
		<div class="panel-heading" style="overflow:hidden;">
			<h1 class="pull-left ml-3 mb-2">
				<i class="fas fa-briefcase"></i> Cases
			</h1>
			<div class="clearfix"></div>
			<p class="ml-3 mb-2">Cases shows all of your case information regarding all cases.  Click on a case to show information about that case.</p>							
			@include('dashboard.includes.alerts')

			@if (count($cases) === 0)
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					No cases for this user, yet! <strong>Add a new case above!</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			@endif
		</div>
		<div class="panel-body">
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
						<label>Hours</label>
						<input type="text" class="form-control" name="hours" aria-label="Hours worked">
					</div>					 
					<div class="col-sm-6 col-xs-12">
						<label>Fixed rate</label>
						<input type="radio" name="rate_type" value="fixed" aria-label="Fixed rate">
						<label>Hourly rate</label>
						<input type="radio" name="rate_type" value="hourly" aria-label="Hourly rate">
					</div>
					<div class="col-12">
						<button class="btn btn-primary mt-3"><i class="fas fa-check"></i> Submit</button>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>

@endsection