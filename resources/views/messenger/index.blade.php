@extends('layouts.dashboard')

@section('content')
<div class="container dashboard messages home col-sm-10 col-xs-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info"  href="/dashboard/messages"><i class="fas fa-envelope"></i> My messages</a>    
  </nav>

    @include('messenger.partials.flash')
    <h1 class="mb-2 ml-3"><i class="fas fa-quote-left"></i> Messages</h1>
   				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Clients shows all of your client information regarding all cases.  Click on a client to show information.</p>							
						@include('dashboard.includes.alerts')  
  <div class="col-sm-6 col-12">
      
    <div class="col-12">
      <div class="mt-3">
    @each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')
      </div>
    </div>
  </div>
  
  <div class="col-sm-6 offset-sm-6 col-12">
    @include('messenger.create', $users)
  </div>
  
</div>
      
@stop
