@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-10 col-12 offset-sm-2">


	<div id="app">
	  <passport-clients></passport-clients>
	  <passport-authorized-clients></passport-authorized-clients>
	  <passport-personal-access-tokens></passport-personal-access-tokens>
	</div>

@endsection