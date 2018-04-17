<?php $class = $thread->isUnread(Auth::id()) ? 'alert-primary' : 'alert-light'; ?>
<div class="index-message">
  <div class="thread-author-image">
    <img src="//www.gravatar.com/avatar/{{ md5($thread->creator()->email) }} ?s=64" alt="{{ $thread->creator()->name }}" class="img-square">

  </div>
  <div class="media speech-bubble {{ $class }}"> 
      <div class="extra-info">
       <h6 class="media-heading">
         <a href="#here" id="thread-{{ $thread->id }}"><i class="fas fa-quote-left"></i> <strong>{{ $thread->subject }}</strong> <i class="fas fa-quote-right"></i></a>
      </h6> 

      <span>
          <small><strong>Author:</strong> {{ $thread->creator()->name }}</small>
      </span>
        </div>
  </div>
</div>

