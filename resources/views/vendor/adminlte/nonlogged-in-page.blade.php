@extends('adminlte::master')

@section('adminlte_css')
  <link rel="stylesheet"
		href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

  @stack('css')
  @yield('css')

  <style>
	@import url('https://fonts.googleapis.com/css?family=Montserrat:300|Open+Sans:300');
  </style>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet"
		type="text/css">
  <link href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css">
  <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet'>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"
		  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
		  integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
		  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script src="{{ asset('js/timer.js') }}"></script>
  <script type="text/javascript" src="https://cdn.knightlab.com/libs/timeline/latest/js/timeline-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
  <script src="{{ asset('js/moment.js') }}"></script>
  <script src="{{ asset('js/autocomplete.js') }}"></script>



@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
'boxed' => 'layout-boxed',
'fixed' => 'fixed',
'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')

  <div class="wrapper non-logged-in">


  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
	  @if(config('adminlte.layout') == 'top-nav')
		<div class="container">
		@endif

		<!-- Main content -->
		  <section class="content">
              @if(\Auth::user())
              <p>You may send this link to a client to pay.</p>
			  @endif

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

@stop


@section('adminlte_js')

<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@stack('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
<script src=//cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js charset=utf-8></script>
<script src=//cdn.jsdelivr.net/npm/fusioncharts@3.12.2/fusioncharts.js charset=utf-8></script>
<script src=//cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('ckeditor/adapters/jquery.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.extensions.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.numeric.extensions.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/inputmask.phone.extensions.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('js/input-mask/dist/inputmask/phone-codes/phone.js') }}"></script>
<script src="{{ asset('js/datepicker/datepicker.min.js') }}"></script>
<script src="{{ asset('js/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="https://www.datejs.com/build/date.js" type="text/javascript"></script>
<script src="{{ asset('js/tablesorter.js') }}"></script>
<script src="{{ asset('js/tagify.js') }}"></script>
<script src="{{ asset('js/match-height.js') }}"></script>
<script src="{{ asset('js/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('js/datepicker/datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
@yield('js')
@stop
