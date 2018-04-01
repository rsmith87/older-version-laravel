<!DOCTYPE html>
<html>
  <head>
    <title>Legality</title>
    <meta name="viewport" content="width=device-width, user-scalable=false;">
		<meta name="robots" content="noindex">
		<meta name="google-signin-client_id" content="{{ env('GOOGLE_CLIENT_ID') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/app.css" rel="stylesheet" type="text/css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script src="//apis.google.com/js/platform.js" async defer></script>		


		
  </head>
  <body class="home">
    <div class="container">
      
<nav class="navbar navbar-default navbar-inverse" role="navigation" class="dashboard-navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="#">Legality</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">About</a></li>
        <li><a href="#">Teamt</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Product <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Scheduling</a></li>
            <li><a href="#">Document Repository</a></li>
            <li><a href="#">Client Portal</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><p class="navbar-text">Already have an account?</p></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
			<ul id="login-dp" class="dropdown-menu">
				<li>
					 <div class="row">
							<div class="col-md-12">
								Login via
								<div class="social-buttons">
									<a href="/login/facebook" class="btn btn-fb"><i data-fa-transform="grow-x" class="fa fa-facebook"></i> Facebook</a>
									<a href="/login/google" class="btn btn-google"><i class="fa fa-google"></i> Google</a>
								</div>
                                or
								 <form class="form" role="form" method="post" action="/login" accept-charset="UTF-8" id="login-nav">
									           <input type="hidden" name="_token" value="{{ csrf_token() }}">
										<div class="form-group">
											 <label class="sr-only" for="exampleInputEmail2">Email address</label>
											 <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email address" required>
										</div>
										<div class="form-group">
											 <label class="sr-only" for="exampleInputPassword2">Password</label>
											 <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
                                             <div class="help-block text-right"><a href="">Forget the password ?</a></div>
										</div>
										<div class="form-group">
											 <button type="submit" class="btn btn-primary btn-block">Sign in</button>
										</div>
										<div class="checkbox">
											 <label>
											 <input type="checkbox"> keep me logged-in
											 </label>
										</div>
								 </form>
							</div>
							<div class="bottom text-center">
								New here ? <a href="/register"><b>Join Us</b></a>
							</div>
					 </div>
				</li>
			</ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
  
      @yield('content')

  </div>
 </body>
</html>
