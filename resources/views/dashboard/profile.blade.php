@extends('adminlte::page')

@section('content')

  <div class="container fill dashboard profile col-sm-12 col-12 offset-sm-2">

	@include('dashboard.includes.alerts')


	<h1 class="mb-2 ml-3">
	  <i class="fa fa-user"></i> User profile
	</h1>
	<div class="clearfix"></div>


	<div class="row">
	  <div class="col-md-3">

		<!-- Profile Image -->
		<div class="box box-primary">
		  <div class="box-body box-profile">
			  @if($settings->profile_image != "")
				<img class="profile-user-img img-responsive img-circle" src="https://{{ env('APP_DOMAIN') }}{{ $settings->profile_image }}"
					 alt="User profile picture">
			  @else
				<i class="fas fa-user-circle fa-7x"></i>
			  @endif

			<h3 class="profile-username text-center">{{ $user->name }}</h3>

			<p class="text-muted text-center">{{ $settings->title }}</p>

		  </div>
		  <!-- /.box-body -->
		</div>
		<!-- /.box -->

		<!-- About Me Box -->
		<div class="box box-primary">
		  <div class="box-header with-border">
			<h3 class="box-title">About Me</h3>
		  </div>
		  <!-- /.box-header -->
		  <div class="box-body">
			<strong><i class="fa fa-book margin-r-5"></i> Education</strong>

			<p class="text-muted">
			  {{ $settings->education }}
			</p>

			<hr>

			<strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

			<p class="text-muted">{{ $settings->location }}</p>


		  </div>
		  <!-- /.box-body -->
		</div>
		<!-- /.box -->
	  </div>
	  <!-- /.col -->
	  <div class="col-md-9">
		<div class="nav-tabs-custom">
		  <ul class="nav nav-tabs">
			<li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">Settings</a></li>
			<li><a href="#cancel-account" data-toggle="tab" aria-expanded="false">Cancel account</a></li>

		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="settings">
			  <form class="form-horizontal" method="post" action="/dashboard/profile-update"
					enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
				<input type="hidden" name="u_id" value="{{ $user->id }}"/>
				<div class="form-group">
				  <label for="inputName">Name</label>


					<input class="form-control" id="inputName" name="name" value="{{ $user->name }}" placeholder="Name">
				</div>
				<div class="form-group">
				  <label for="inputSkills">Title</label>

					<input type="text" class="form-control" value="{{ $settings->title }}" id="inputSkills" name="title"
						   placeholder="Title">
				</div>
				<div class="form-group">
				  <label for="profile-image">Profile image</label>

					<input type="file" class="form-control" name="file_upload"/>
				</div>
				<div class="form-group">
				  <label for="inputEmail">Email</label>

					<input type="email" class="form-control" id="inputEmail" name="email" value="{{ $user->email }}"
						   placeholder="Email">
				</div>
				<div class="form-group">
				  <label for="inputExperience">Education</label>

					<textarea class="form-control" id="inputExperience" name="education"
							  placeholder="Education">{{ $settings->education }}</textarea>
				</div>
				<div class="form-group">
				  <label for="inputExperience">Location</label>

					<textarea class="form-control" id="inputExperience" name="location"
							  placeholder="Location">{{ $settings->location }}</textarea>
				</div>

				<div class="form-group">
				  <div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Submit</button>
				  </div>
				</div>
			  </form>
			</div>
			<div class="tab-pane" id="cancel-account">


				<form class="form-horizontal" method="post" action="/dashboard/account/cancel">
					  <input type="hidden" name="_token" value="{{ csrf_token() }}">


					  <p>To cancel your Litimate subscription, click the button below.</p>


					  <button id="submit" name="submit" class="btn btn-danger">Cancel account</button>

			  </form>
			</div>
			<!-- /.tab-pane -->
		  </div>
		  <!-- /.tab-content -->
		</div>
		<!-- /.nav-tabs-custom -->
	  </div>
	  <!-- /.col -->
	</div>

  @include('dashboard.includes.event-modal')
	  <div class="modal fade" id="cancel-subscription">
		  <div class="modal-dialog">
			  <div class="modal-content">
				  <div class="modal-body">
					  <h3>
						  <i class="fas fa-building"></i> Edit firm information
					  </h3>

					  <div class="clearfix"></div>
					  <hr />
					  <form class="form-horizontal" method="post" action="/dashboard/firm/message/add">
						  <input type="hidden" name="_token" value="{{ csrf_token() }}">


						  <p>To cancel your Litimate subscription, click the button below.</p>


						  <button id="submit" name="submit" class="btn btn-primary">Submit</button>

					  </form>
				  </div>
			  </div>
		  </div>
	  </div>
@endsection
