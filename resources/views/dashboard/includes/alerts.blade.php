@if (session('status'))
	<div class="alert alert-success fade in ml-3 mr-3 mb-4">
		{{ session('status') }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>			
	</div>
@endif
	
@if ($errors)
	@foreach ($errors->all() as $message) 
		<div class="alert alert-danger fade in ml-3 mr-3 mb-4">	
			{{ $message }}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>		 
	@endforeach 
@endif

@if ($firm_id === "" || $firm_id === 0)
	<div class="alert alert-danger alert-dismissible fade in mb-4" role="alert">
		You have not created or assigned yourself to a firm!
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
@endif		