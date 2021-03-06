@extends('adminlte::master')

@section('adminlte_css')
  <link rel="stylesheet"
		href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
  <link href="{{ asset('css/select2.css') }}" rel="stylesheet" />

  @stack('css')
  @yield('css')

  <style>
	/*@import url('https://fonts.googleapis.com/css?family=Montserrat:300|Open+Sans:300');*/
  </style>
  <link href="{{ asset('css/fullcalendar.css') }}" rel="stylesheet"
		type="text/css">
  <link href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css">
  <!--<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet'>-->
  <link href="{{ asset('css/pretty-checkbox.min.css') }}" />


@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
'boxed' => 'layout-boxed',
'fixed' => 'fixed',
'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')

  <div class="wrapper">

	<!-- Main Header -->
	<header class="main-header">
	  @if(config('adminlte.layout') == 'top-nav')
		<nav class="navbar navbar-static-top">
		  <div class="container">
			<div class="navbar-header">
			  <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
				{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
			  </a>
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
					  data-target="#navbar-collapse">
				<i class="fa fa-bars"></i>
			  </button>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
			  <ul class="nav navbar-nav">
				@each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
			  </ul>
			</div>
			<!-- /.navbar-collapse -->
		  @else

			<!-- Logo -->
			  <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
			  </a>

			  <!-- Header Navbar -->
			  <nav class="navbar navbar-static-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				  <i class="far fa-minus-square"></i> <span
						  class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
				</a>
			  @endif

			  <!-- Navbar Right Menu -->
				<div class="navbar-custom-menu">

				  <ul class="nav navbar-nav">

					<li>
					  <a class="timer-create" href="#" data-target="#timer-modal" data-toggle="modal"><i
								class="fas fa-stopwatch"></i></a>
					</li>

					  <!--<li class="dropdown">
						<a id="dLabel" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true"
						   aria-expanded="false">
						  <i class="fas fa-comments"></i>
						</a>
						<ul class="dropdown-menu" aria-labelledby="dLabel">


						</ul>
					  </li>-->


					  </li>

					<li>
					  <a href="/dashboard/lock"><i class="fas fa-lock"></i></a>
					</li>
					<li>
					  @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
						<a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
						  <i class="fas fa-sign-out-alt"></i>
						</a>
					  @else


						<a href="#"
						   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
						>
						  <i class="fas fa-sign-out-alt"></i>
						</a>
						<form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}"
							  method="POST" style="display: none;">
						  @if(config('adminlte.logout_method'))
							{{ method_field(config('adminlte.logout_method')) }}
						  @endif
						  {{ csrf_field() }}
						</form>
					  @endif
					</li>
					<!--<li>
					  <a data-toggle="control-sidebar"><i class="fas fa-cogs"></i></a>
					</li>-->
				  </ul>
				</div>
			  @if(config('adminlte.layout') == 'top-nav')
		  </div>
		  @endif
		</nav>
	</header>

  @if(config('adminlte.layout') != 'top-nav')
	<!-- Left side column. contains the logo and sidebar -->
	  <aside class="main-sidebar">

		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
		  <!-- Sidebar user panel -->
		  <div class="user-panel">

				@if($settings->profile_image != "")
				  <div class="pull-left image">

				  <img class="img-circle" src="{{ $settings->profile_image }}"
						 alt="User profile picture">
				  </div>


				@else
				  <i class="fas fa-user-circle fa-3x"></i>

			  @endif



			<div class="pull-left info">
			  <p>{{ $user->name }}</p>
			  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		  </div>
		  <!-- search form -->
		  <!--<form action="#" method="get" class="sidebar-form">
			<div class="input-group">
			  <input type="text" name="q" class="form-control" placeholder="Search...">
			  <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
			</div>
		  </form>-->
		  <!-- /.search form -->
		  <!-- Sidebar Menu -->
		  <ul class="sidebar-menu" data-widget="tree">
			@each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
		  </ul>
		  <!-- /.sidebar-menu -->
		</section>
		<!-- /.sidebar -->
	  </aside>
  @endif

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
	  @if(config('adminlte.layout') == 'top-nav')
		<div class="container">
		@endif

		<!-- Main content -->
		  <section class="content">

			@yield('content')

		  </section>
		  <!-- /.content -->
		  @if(config('adminlte.layout') == 'top-nav')
		</div>
		<!-- /.container -->
	  @endif
	</div>
	<!-- /.content-wrapper -->

  </div>
  <!-- ./wrapper -->
  @include('dashboard.includes.timer-modal')
<!-- The Right Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Content of the sidebar goes here -->





   <div class="settings-pane">
	 <input type="checkbox" class="form-control" name="create_tasklist_on_case_create_global">
	 <span>Create tasklist on case creation?</span>
   </div>

  <div class="settings-pane">
	<input type="checkbox" class="form-control" name="create_document_repo_on_case_create_global">
	<span>Create document/media location for case on case creation?</span>

  </div>


</aside>
<!-- The sidebar's background -->
<!-- This div must placed right after the sidebar for it to work-->
<div class="control-sidebar-bg"></div>

@stop


@section('adminlte_js')

<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@stack('js')
<script src="{{ asset('js/popper.js') }}"></script>
<script src="{{ asset('js/select2.js') }}"></script>
<script src="{{ asset('js/list.min.js') }}"></script>
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/autocomplete.js') }}"></script>
<script src="{{ asset('js/fullcalendar.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.extensions.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.numeric.extensions.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.phone.extensions.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/phone-codes/phone.js') }}"></script>
<script src="{{ asset('js/datepicker/datepicker.min.js') }}"></script>
<script src="{{ asset('js/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('js/date.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/tablesorter.js') }}"></script>
<script src="{{ asset('js/tagify.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
@yield('js')
@stop
