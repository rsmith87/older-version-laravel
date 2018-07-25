@extends('adminlte::page')

@section('content')

  <div class="container fill dashboard col-sm-12 col-12 offset-sm-2">

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
			  @if(isset($settings->profile_image) && !Gravatar::exists($user->email))
				<img class="profile-user-img img-responsive img-circle" src="/storage{{ $settings->profile_image }}"
					 alt="User profile picture">
			  @elseif(isset($settings->profile_image) && Gravatar::exists($user->email))
				<img class="profile-user-img img-responsive img-circle" src="{{ Gravatar::get($user->email) }}"
					 alt="User profile picture">>
			  @elseif(Gravatar::exists($user->email))
				<img class="profile-user-img img-responsive img-circle" src="{{ Gravatar::get($user->email) }}"
					 alt="User profile picture">>
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
			<!--<li><a href="#social-media" data-toggle="tab" aria-expanded="false">Social Media</a></li>-->

		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="settings">
			  <form class="form-horizontal" method="post" action="/dashboard/profile-update"
					enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
				<input type="hidden" name="u_id" value="{{ $user->id }}"/>
				<div class="form-group">
				  <label for="inputName" class="col-sm-2 control-label">Name</label>

				  <div class="col-sm-10">
					<input class="form-control" id="inputName" name="name" value="{{ $user->name }}" placeholder="Name">
				  </div>
				</div>
				<div class="form-group">
				  <label for="inputSkills" class="col-sm-2 control-label">Title</label>

				  <div class="col-sm-10">
					<input type="text" class="form-control" value="{{ $settings->title }}" id="inputSkills" name="title"
						   placeholder="Title">
				  </div>
				</div>
				<div class="form-group">
				  <label for="profile-image" class="col-sm-2 control-label">Profile image</label>

				  <div class="col-sm-10">
					<input type="file" class="form-control" name="file_upload"/>
				  </div>
				</div>
				<div class="form-group">
				  <label for="inputEmail" class="col-sm-2 control-label">Email</label>

				  <div class="col-sm-10">
					<input type="email" class="form-control" id="inputEmail" name="email" value="{{ $user->email }}"
						   placeholder="Email">
				  </div>
				</div>
				<div class="form-group">
				  <label for="inputExperience" class="col-sm-2 control-label">Education</label>

				  <div class="col-sm-10">
					<textarea class="form-control" id="inputExperience" name="education"
							  placeholder="Education">{{ $settings->education }}</textarea>
				  </div>
				</div>
				<div class="form-group">
				  <label for="inputExperience" class="col-sm-2 control-label">Location</label>

				  <div class="col-sm-10">
					<textarea class="form-control" id="inputExperience" name="location"
							  placeholder="Location">{{ $settings->location }}</textarea>
				  </div>
				</div>

				<div class="form-group">
				  <div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Submit</button>
				  </div>
				</div>
			  </form>
			</div>
			<div class="tab-pane" id="social-media">

			  <form method="POST" action="/dashboard/settings/social-media">
				<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
				<label>Facebook URL</label>
				<input type="text" class="form-control" value="{{ count($settings->fb) > 0 ? $settings->fb : "" }}"
					   name="fb"/>
				<label>Twitter URL</label>
				<input type="text" class="form-control"
					   value="{{ count($settings->twitter) > 0 ? $settings->twitter : "" }}" name="twitter"/>
				<label>Instagram URL</label>
				<input type="text" class="form-control"
					   value="{{ count($settings->instagram) > 0 ? $settings->instagram : "" }}" name="instagram"/>
				<label>Avvo URL</label>
				<input type="text" class="form-control" value="{{ count($settings->avvo) > 0 ? $settings->avvo : "" }}"
					   name="avvo"/>
				<div class="clearfix"></div>
				<button type="submit" class="btn btn-primary btn-block" style="margin-top:10px;">
				  Submit
				</button>
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

@endsection
