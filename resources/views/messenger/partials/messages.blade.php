<div class="media">
  <img src="//www.gravatar.com/avatar/{{ md5($message->user->email) }} ?s=64" alt="{{ $message->user->name }}" class="img-circle">
 
    <div class="media-body">
        <h5 class="media-heading">{{ $message->user->name }}</h5>
        <div class="text-muted">
            <small>Sent {{ $message->created_at->diffForHumans() }}</small>
        </div>
        <p>{{ $message->body }}</p>
    </div>
</div>