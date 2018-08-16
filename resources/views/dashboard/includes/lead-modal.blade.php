<div class="modal fade" id="leads-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">

				<form method="post" action="/dashboard/leads/add">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<h3>
						<i class="fas fa-user-circle"></i> Add a lead
					</h3>
					<div class="clearfix"></div>
					<hr />

					<div class="col-sm-6 col-12">
						<label>First name</label>
						<input type="text" class="form-control" name="first_name" aria-label="First Name">
					</div>  
					
					<div class="col-sm-6 col-12">
						<label>Last name</label>
						<input type="text" class="form-control" name="last_name" aria-label="Last Name">
					</div>

					
					<div class="col-sm-6 col-12">
						<label>Company</label>
						<input type="text" class="form-control" name="company" aria-label="Company">
					</div> 
					
					<div class="col-sm-6 col-12">
						<label>Company title</label>
						<input type="text" class="form-control" name="company_title" aria-label="Company title">
					</div> 
					
					<div class="col-sm-6 col-12">
						<label>Phone</label>
						<input type="text" class="form-control" id="phone" name="phone" aria-label="Phone">
					</div>   
					
					<div class="col-sm-6 col-12">
						<label>Email</label>
						<input type="text" class="form-control" name="email" aria-label="Email">
					</div> 
					
					<div class="col-sm-6 col-12">
						<label>Address 1</label>
						<input type="text" class="form-control" name="address_1" aria-label="Address">
					</div> 
					
					<div class="col-sm-6 col-12">
						<label>Address 2</label>
						<input type="text" class="form-control" name="address_2" aria-label="Address">
					</div> 	
					
					<div class="col-sm-6 col-12">
						<label>City</label>
						<input type="text" class="form-control" name="city" aria-label="Address">
					</div> 	 
					
						<div class="col-sm-6 col-12">
						<label>State</label>
						<input type="text" class="form-control" name="state" aria-label="Address">
					</div>
					
					<div class="col-sm-6 col-12">
						<label>Zip</label>
						<input type="text" class="form-control" name="zip" aria-label="Address">
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-12">
						<button class="btn btn-primary mt-3 mb-1">
							<i class="fas fa-check"></i> Submit
						</button>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
