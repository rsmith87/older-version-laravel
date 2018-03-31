@extends('layouts.dashboard')

@section('content')
<div class="container dashboard messages home col-sm-10 col-xs-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info"  href="/dashboard/messages"><i class="fas fa-envelope"></i> My messages</a>    
  </nav>

    @include('messenger.partials.flash')
  <div class="col-sm-6 col-12">
      <h1 class="mb-5"><i class="fas fa-quote-left"></i> Messages</h1>
   
    <div class="col-12">
    @each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')
    </div>
  </div>
  
  <div class="col-sm-6 offset-sm-6 col-12">
    @include('messenger.create', $users)
  </div>
  
</div>
      
@stop
