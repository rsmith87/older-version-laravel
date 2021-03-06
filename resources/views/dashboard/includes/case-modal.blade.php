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
						<label>Case Type</label>
						<select class="form-control" id="inputGroupSelect01" name="type" aria-label="Status" aria-describedby="inputGroup-sizing-sm">
			        @foreach($case_types as $t)
         	      <option value={{ $t }}>{{ str_replace('_', ' ', ucwords($t)) }}</option>
			        @endforeach 
						</select>					</div>       
					<div class="col-sm-12 col-xs-12">
						<label>Name</label>
						<input type="text" class="form-control" id="name" name="name" aria-label="Name">
					</div>   
					<div class="col-sm-12">
						<label>Description</label>
						<textarea class="form-control" aria-label="Description" name="description" id="description"></textarea>
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Case Number</label>
						<input type="text" class="form-control" id="case_number" name="case_number" aria-label="Case Number">
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
						<div class="col-sm-6 col-xs-12 col-no-pad-left">
							<label>City</label>
							<input type="text" class="form-control" id="city" name="city" aria-label="City">
						</div>
						<div class="col-sm-6 col-xs-12 col-no-pad">
								<label>State</label>
								<select name="state" class="form-control">
								@foreach($states as $state)
									<option value="{{ $state }}">{{ $state }}</option>
								@endforeach
								</select>
							</div>
					</div>   
					<div class="col-sm-6 col-xs-12">
						<label>Claim Reference Number</label>
						<input type="text" class="form-control" id="claim_reference_number" name="claim_reference_number" aria-label="Claim reference number">
					</div>   
					<div class="col-sm-6 col-xs-12">
						<label>Open date</label>
						<input type="text" class="form-control dp" data-toggle="dp" id="open_date" name="open_date" aria-label="Open date">
					</div>  
					<div class="col-sm-6 col-xs-12">
						<label>Close date</label>
						<input type="text" class="form-control dp" data-toggle="dp" id="close_date" name="close_date" aria-label="Close date">
					</div>
					<div class="col-sm-6 col-xs-12">
					  <label>Rate</label>
					  <div class="input-group">
						<span class="input-group-addon">$</span>
						<input type="text" class="form-control" name="billing_rate" aria-label="Amount (to the nearest dollar)">
					  </div>
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Hours worked</label>
						<input type="text" class="form-control" name="hours" aria-label="Hours worked">
					</div>
					<div class="col-sm-6 col-xs-12 mt-4">
						<label>Fixed rate</label>
						<input type="radio" name="rate_type" value="fixed" aria-label="Fixed rate">
						<label>Hourly rate</label>
						<input type="radio" name="rate_type" value="hourly" aria-label="Hourly rate">
					</div>
				  <div class="col-sm-6 col-xs-12 mt-4">
					<label>Statute of Limitations</span>
					  <input type="checkbox" name="statute_of_limitations" aria-label="Statute of Limitations">
				  </div>
				  	<div class="col-sm-6 col-xs-12">
					  <label>Create task list?</label>
					  <input type="checkbox" class="form-control" name="create_case_task_list">
					  <div class="clearfix"></div>
					  <span>This will create a <a href="/dashboard/tasklists">task list</a> named by the case name.</span>
					</div>
				  <!--<div class="col-sm-6 col-xs-12">
					<label>Create document/media directory?</label>
					<input type="checkbox" class="form-control" name="create_case_document_directory">
					<div class="clearfix"></div>
					<span>This will create a document/media directory named after the case in your user <a href="/dashboard/documents">document repository</a>.</span>
				  </div>-->
					<div class="col-12">
						<button class="btn btn-primary mt-3"><i class="fas fa-check"></i> Submit</button>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>