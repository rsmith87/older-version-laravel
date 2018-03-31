<!DOCTYPE html>
<html>
  <head>
    <title>Legaleeze</title>
    <meta name="viewport" content="width=device-width">
		<meta name="robots" content="noindex">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
		
<style>
@import url('https://fonts.googleapis.com/css?family=Montserrat:300|Open+Sans:300');
</style>
		<link rel="stylesheet" href="{{ asset('css/'.$theme.'.bootstrap.min.css') }}" type="text/css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" type="text/css">
		<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
		<link href="{{ asset('js/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
		<link href="{{ asset('js/timepicker/jquery.timepicker.min.css') }}" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
		<script src="{{ asset('js/timer.js') }}"></script>
		<!-- development version, includes helpful console warnings -->
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

		

	</head>
  <body data-spy="scroll" data-target="#navbar-interior" data-offset="70">
		<nav class="navbar navbar-expand-lg dashboard-navigation navbar-dark bg-primary col-12 col-sm-2">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

			<div class="collapse navbar-collapse" id="navbarColor01">

       <ul class="navbar-nav mr-auto">
        <li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/cases"><i data-fa-transform="grow-2" class="fas fa-briefcase fa-fw fa-sm"></i>Cases</a></li>
				@if(!$role)
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/firm"><i class="fas fa-building fa-fw fa-sm"></i>Firm</a></li>
				@endif
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/clients"><i data-fa-transform="grow-2" class="fas fa-users fa-fw fa-sm"></i>Clients</a></li>
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/contacts"><i class="fas fa-address-card fa-fw fa-sm"></i>Contacts</a></li>
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/calendar"><i class="fas fa-calendar-alt fa-sm fa-fw"></i>Calendar</a></li>
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/messages"><i class="fas fa-comment-alt fa-fw fa-sm"></i>Messaging</a></li>
			  <li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/marketing"><i class="fas fa-dollar-sign fa-fw fa-sm"></i>Marketing</a></li>
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/tasks"><i class="fas fa-tasks fa-fw fa-sm"></i>Tasks</a></li>
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/documents"><i class="fas fa-file fa-fw fa-sm"></i>Documents</a></li>
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/reports"><i class="fas fa-chart-line fa-sm fa-fw"></i>Reports</a></li>				
				<li class="nav-item btn-primary"><a class="nav-link" href="/dashboard/settings"><i class="fas fa-cog fa-sm fa-fw"></i>Settings</a></li>
				 <li> @include('dashboard.includes.navtasks')</li> 
				

				
      </ul>
			      
  
      

    </div><!-- /.navbar-collapse -->
			

		
	
		
		</nav>		
		
	
		@yield('content')

		<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="{{ asset('js/moment.js') }}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
		<script src="{{ asset('js/match-height.js') }}"></script>
	  <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
	  <script src="{{ asset('ckeditor/adapters/jquery.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/pair-select.min.js') }}"></script>
		<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.js') }}"></script>
		<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.extensions.js') }}"></script>
		<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.numeric.extensions.js') }}"></script>
		<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.date.extensions.js') }}"></script>
		<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.phone.extensions.js') }}"></script>
		<script src="{{ asset('js/input-mask/dist/inputmask/jquery.inputmask.js') }}"></script>
		<script src="{{ asset('js/input-mask/dist/inputmask/phone-codes/phone.js') }}"></script>
		<script src="{{ asset('js/datepicker/datepicker.min.js') }}"></script>
		<script src="{{ asset('js/timepicker/jquery.timepicker.min.js') }}"></script>
		<script src="http://www.datejs.com/build/date.js" type="text/javascript"></script>
		<script src="{{ asset('js/tablesorter.js') }}"></script>		
		<script src="{{ asset('js/scripts.js') }}"></script>
		<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

 </body>
</html>
