@extends('layouts.dashboard')

@section('content')
<div class="container documents dashboard col-sm-10 col-12 offset-sm-2">
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#documents-modal" href="#"><i class="fa fa-plus"></i> <i class="fas fa-cloud-upload-alt"></i> Upload Document</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#wysiwyg-modal" href="#"><i class="fa fa-plus"></i> <i class="fas fa-file"></i> Create Document</a>
	</nav> 	

	@include('dashboard.includes.alerts')

	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading" style="overflow:hidden;">
				<h1 class="ml-3 mt-4">
					<i class="fas fa-file"></i> Documents
				</h1>
			</div>
			<div class="panel-body">

				@if (count($documents) === 0)
					<div class="alert alert-warning alert-dismissible fade in" role="alert">
						You haven't upoaded a document yet! <strong>Upload or add a new document above!</strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				@endif





				@if (count($documents) > 0)		
					<table class="table table-responsive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
						<thead> 
							<tr> 
								<th>id</th>
								<th>Name</th>
								<th>Description</th>
								<th>Path</th>
							</tr> 
						</thead> 
						<tbody> 			 
						@foreach($documents as $document)
							<tr>
								<td>{{ $document->id }}</td>
								<td scope="row"><a target="_blank" href="http://s3.amazonaws.com/legaleeze{{ $document->path }}">{{ $document->name }}</a></td>
								<td>{{ $document->description }}</td> 
								<td>Stored securely on {{ $document->location }} on {{ $document->path }}</td>
							</tr> 
						@endforeach
						</tbody> 
					</table>
				@endif
			</div>
		</div>
	</div>   
</div>

<div class="modal fade" id="documents-modal">
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


<div class="modal fade" id="wysiwyg-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
					<i class="fas fa-cloud-upload-alt"></i> Upload document
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form method="post" action="/dashboard/documents/create" enctype="multipart/form-data">		 
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-sm-6 col-12">
						<label for="file_name">File Name</label>
						<input type="text" class="form-control" name="file_name" placeholder="File Name" />
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-6 col-12">
						<label for="file_description">File Description</label>
						<input type="text" class="form-control" name="file_description" placeholder="File Description" />
					</div>
					<div class="col-12">
						<textarea id="ckeditor_one" name="ckeditor_one"></textarea>
					</div>
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


<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
CKEDITOR.replace('ckeditor_one');
</script>
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
@endsection