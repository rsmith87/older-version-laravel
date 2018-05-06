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
            <div class="clearfix"></div>
						<select class="form-control" id="inputGroupSelect01" name="status" aria-label="Status" aria-describedby="inputGroup-sizing-sm">
			        @foreach($status_values as $t)
         	      <option value={{ $t }}>{{ ucwords($t) }}</option>
			        @endforeach 
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
						<label>Rate</label>
						<input type="text" class="form-control" name="billing_rate" aria-label="Amount (to the nearest dollar)">
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Hours</label>
						<input type="text" class="form-control" name="hours" aria-label="Hours worked">
					</div>	
					<div class="col-sm-6 col-xs-12 mt-4">
						<label>Statute of Limitations</span>
						<input type="checkbox" name="statute_of_limitations" aria-label="Statute of Limitations">
					</div>
					<div class="col-sm-6 col-xs-12 mt-4">
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