<div class="modal fade" id="document-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
				<i class="fas fa-cloud-upload-alt"></i> Upload document
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form method="post" action="/dashboard/documents/upload" enctype="multipart/form-data">		 
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-sm-6 col-12">
						<label for="file_upload">File</label>
						<input type="file" class="form-control" name="file_upload" />
					</div>
					<div class="col-sm-6 col-12">
						<label for="file_name">File Name</label>
						<input type="text" class="form-control" name="file_name" placeholder="File Name" />
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-6 col-12">
						<label for="file_description">File Description</label>
						<input type="text" class="form-control" name="file_description" placeholder="File Description" />
					</div>
					<div class="clearfix"></div>
					<hr />
					@hasanyrole('authenticated_user|administrator')
					<div class="col-sm-6 col-12">
						<label for="case_name">Case link</label>
						<input type="hidden" name="case_id" />
						<input type="text" name="case_name" class="form-control" placeholder="Case name" />
					</div>
					<div class="col-sm-6 col-12">
						<label for="client_name">Client link</label>
						<input type="hidden" name="client_id" />
						<input type="text" name="client_name" class="form-control" placeholder="Client name" />
					</div>
					<div class="col-sm-6 col-12">
						<label for="contact_name">Contact link</label>
						<input type="hidden" name="contact_id" />
						<input type="text" name="contact_name" class="form-control" placeholder="Contact name" />
					</div>
					<div class="col-sm-6 col-12">
						<label for="file_name">Share with client?</label>
						<input type="checkbox" class="form-control" name="client_share" />
					</div>
					@endhasanyrole
					<div class="col-12">
						<button class="btn btn-primary mt-3 mb-3" type="submit">
							<i class="fas fa-check"></i> Submit
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>  

<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
var cases = {!! json_encode($cases->toArray()) !!};
var clients = {!! json_encode($clients->toArray()) !!};
var contacts = {!! json_encode($contacts->toArray()) !!};	

  for(var i = 0; i<cases.length; i++){
	  cases[i].data = cases[i]['id'];
	  cases[i].value = cases[i]['name'];
	}
	for(var i = 0; i<clients.length; i++){
		clients[i].data = clients[i]['id'];
		clients[i].value = clients[i]['first_name'] + " " + clients[i]['last_name'];
	}
	for(var i = 0; i<contacts.length; i++){
		contacts[i].data = contacts[i]['id'];
		contacts[i].value = contacts[i]['first_name'] + " " + contacts[i]['last_name'];
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
	$('input[name="client_name"]').autocomplete({
    lookup: clients,
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
	$('input[name="contact_name"]').autocomplete({
    lookup: contacts,
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