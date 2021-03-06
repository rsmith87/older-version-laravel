<div class="modal fade" id="client-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<form method="post" action="/dashboard/contacts/add">

					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="is_client" value="1" />
					<input type="hidden" name="type" value="client">

				    @if(isset($requested_case) && count($requested_case) > 0)
					  <input type="hidden" name="case_id" value="{{ $requested_case->id }}" />
					@endif
				  	@if(isset($case) && count($case) > 0)
					<input type="hidden" name="case_id" value="{{ $case->id }}" />

				    @endif
					<h3>
						<i class="fas fa-address-card"></i> Add a Client
					</h3>
					<div class="clearfix"></div>
					<hr />

					<div class="col-sm-6 col-xs-12">
							<label>First name</label>
							<input type="text" class="form-control" name="first_name" aria-label="First Name">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Last name</label>
						<input type="text" class="form-control" name="last_name" aria-label="Last Name">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Company</label>
						<input type="text" class="form-control" name="company" aria-label="Company">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Company title</label>
						<input type="text" class="form-control" name="company_title" aria-label="Company title">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Phone</label>
						<input type="text" class="form-control" id="phone" name="phone" aria-label="Phone">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Email</label>
						<input type="text" class="form-control" name="email" aria-label="Email">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Address 1</label>
						<input type="text" class="form-control" name="address_1" aria-label="Address">
					</div> 
					
					<div class="col-sm-6 col-xs-12">
						<label>Address 2</label>
						<input type="text" class="form-control" name="address_2" aria-label="Address">
					</div> 	
					
					<div class="col-sm-6 col-xs-12">
						<label>City</label>
						<input type="text" class="form-control" name="city" aria-label="Address">
					</div> 	 
					
						<div class="col-sm-6 col-xs-12">
						<label>State</label>
						<input type="text" class="form-control" name="state" aria-label="Address">
					</div>
					
					<div class="col-sm-6 col-xs-12">
						<label>Zip</label>
						<input type="text" class="form-control" name="zip" aria-label="Address">
					</div>
					<div class="clearfix"></div>
          			@if(count($cases) > 0 && !isset($case->id))
					<hr />
					<div class="col-sm-12 col-xs-12">
						<label>Case</label>
						<input type="hidden" name="case_id" />
						<input type="text" name="case_name" class="form-control" placeholder="Start typing for autocomplete" />
					</div>   
          			@endif
					<button class="btn btn-primary mt-3 mb-1">
						<i class="fas fa-check"></i> Submit
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
var cases = {!! json_encode($cases->toArray()) !!};

  for(var i = 0; i<cases.length; i++){
	
	  cases[i].data = cases[i]['id'];
	  cases[i].value = cases[i]['name'];

		//console.log(cases[i])
}
	
	 $('input[name="case_name"]').autocomplete({
    lookup: cases,
		width: 'flex',
		triggerSelectOnValidInput: true,
    onSelect: function (suggestion) {
      var thehtml = '<strong>Case '+suggestion.data+':</strong> ' + suggestion.value + ' ';
			//alert(thehtml);
			var $this = $(this);
      $('#outputcontent').html(thehtml);
   		$this.prev().val(suggestion.data);
			
    }
		 
  });
	

</script>