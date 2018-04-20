<div class="col-12">
    @each('messenger.partials.messages', $thread->messages, 'message')
  <div id="chatbox"></div>
    @include('messenger.partials.form-message')
</div>



