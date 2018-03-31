@extends('layouts.dashboard')

@section('content')
<div class="container dashboard messages home col-sm-10 col-xs-12 offset-sm-2">
  <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info"  href="/dashboard/messages"><i class="fas fa-envelope"></i> My messages</a>    
    <a class="nav-item nav-link btn btn-info" href="/dashboard/messages/create"><i class="fas fa-plus"></i> <i class="fas fa-comment"></i> Create message</a>
  </nav>  	

    <div class="col-12">
        <h1>{{ $thread->subject }}</h1>
        @each('messenger.partials.messages', $thread->messages, 'message')
      <div id="chatbox"></div>
        @include('messenger.partials.form-message')
    </div>

</div>

@stop

