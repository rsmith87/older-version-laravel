@extends('adminlte::page')

@section('content')
<script src="{{ asset('vendor/laravel-filemanager/js/lfm.js') }}"></script>

 		
   <div class="panel panel-primary panel-documents">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-4 mt-4 mb-3"> 
         <i class="fas fa-cloud-upload-alt"></i> Documents
        </h1>
        @include('dashboard.includes.alerts')
     </div>
     <div class="panel-body">
		
       
       <iframe src="https://legalkeeper.rob/laravel-filemanager?type=file"></iframe>
 
<script src="{{ asset('js/autocomplete.js') }}"></script>
@if($user->hasRole('administrator') || $user->hasRole('authenticated_user'))
<script type="text/javascript">
	var $ = jQuery;
   $('#lfm').filemanager('image');

   $('#lfm').filemanager('file');
  
	

	
</script>
@endif       



@endsection