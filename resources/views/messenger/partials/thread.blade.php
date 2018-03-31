<?php $class = $thread->isUnread(Auth::id()) ? 'alert-primary' : 'alert-light'; ?>
<div class="index-message">
<img src="//www.gravatar.com/avatar/{{ md5($thread->creator()->email) }} ?s=64" alt="{{ $thread->creator()->name }}" class="img-square">
  <div class="media speech-bubble {{ $class }}"> 
      <div class="extra-info">
       <h4 class="media-heading">
          <a href="{{ route('messages.show', $thread->id) }}"><i class="fas fa-quote-left"></i> {{ $thread->subject }} <i class="fas fa-quote-right"></i></a>
      </h4> 

      <span>
          <small><strong>Author:</strong> {{ $thread->creator()->name }}</small>
      </span>
        </div>
  </div>
</div>