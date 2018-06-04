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
@endif       



@endsection