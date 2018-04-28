<div id="bottom-nav">


@hasanyrole('authenticated_user|administrator')

<div class="nav-clock mt-5 d-none">
  <h3 class="timer">00:00:00</h3>
  <div class="timer-buttons">
    <button type="button" class="btn btn-success btn-sm startButton"><i class="fas fa-play"></i></button>
    <button type="button" class="btn btn-info btn-sm pauseButton"><i class="fas fa-pause"></i></button>
    <button type="button" class="btn btn-danger btn-sm stopButton"><i class="fas fa-stop"></i></button>
  </div>
</div>
@endhasanyrole   
<div class="logo mt-5" style="text-align:center;">
  <a href="/dashboard">
  <img src="{{ asset('img/logo-white.png') }}" class="img-responsive" />
  </a>
  <a href="/logout" class="btn btn-info mt-4">Logout</a>
</div>
  
</div>