@extends('layouts.dashboard')

@section('content')
<div class="container dashboard messages home col-sm-10 col-xs-12 offset-sm-2">
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info"  href="/dashboard/messages"><i class="fas fa-envelope"></i> My messages</a>    
	</nav>

	@include('messenger.partials.flash')
	<h1 class="mb-2 ml-3">
		<i class="fas fa-quote-left"></i> Messages
	</h1>
	
	<div class="clearfix"></div>
	
	<p class="ml-3 mb-2">
		Clients shows all of your client information regarding all cases.  Click on a client to show information.
	</p>	
	
	@include('dashboard.includes.alerts') 
	
	<div class="col-sm-5 col-12">
		<div class="mt-3">
			@each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')
		</div>
	</div>

	<div class="col-sm-7 offset-sm-5 actual-message col-12">

	</div>

	<div class="clearfix"></div>
	<div class="col-12">
		@include('messenger.create', $users)
	</div>
</div>

<script type="text/javascript">
	var threads =  {!! json_encode($threads->toArray()) !!}; 
	var users = {!! json_encode($users->toArray()) !!}

	var u = [];
	for(var i = 0; i < users.length; i++){
		if(users[i].id != null){
			u[users[i].id] = users[i];			
		}
	}

	for(var i = 0; i<threads.length; i++){
		$('#thread-'+threads[i]['id']).on('click', function(){
			var $this = $(this);
			var $id = $(this).attr('id');
			$id = $id.replace('thread-', '');
			perform_ajax($id);
		});			
	}
	
	function perform_ajax($id){	
		$('.actual-message').empty();
		$.ajax({
			type: 'get',
			contentType: "application/json; charset=utf-8",
			url: '/dashboard/messages/ajax/'+$id,
			success: function (data) {
				var users = data.participants;
				var message = data.message;		
				var msg = [];
				$.each(message, function(){
					var $this = $(this); 
					msg.push('<div class="media"><img src="//www.gravatar.com/avatar/" class="img-circle">'+
						'<div class="media-body"><h5 class="media-heading">'+users[$this[0].user_id][0].name+'</h5><div class="text-muted">'+
						'<small>Sent ' + $this[0].created_at + '</small></div><p>'+$this[0].body+'</p></div></div>');						
				});
				$(".actual-message").append(data);					
				}
		});

	}

</script>

@stop
