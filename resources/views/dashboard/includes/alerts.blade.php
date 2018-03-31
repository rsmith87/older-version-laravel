@if (session('status'))
    <div class="alert alert-success fade in">
        {{ session('status') }}
		 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    	<span aria-hidden="true">&times;</span>
  	 </button>			
    </div>
@endif
	
@if ($errors)
		@foreach ($errors->all() as $message) 
   <div class="alert alert-danger fade in">	
    	{{ $message }}
		 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    	<span aria-hidden="true">&times;</span>
  	 </button>
	 </div>		 
		@endforeach 
@endif