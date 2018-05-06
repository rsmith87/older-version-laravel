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
              <img class="profile-user-img img-responsive img-circle" src="{{ Gravatar::get($user->email) }}" alt="User profile picture">

              <h3 class="profile-username text-center">{{ $user->name }}</h3>

              <p class="text-muted text-center">{{ $settings->title }}</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Followers</b> <a class="pull-right">1,322</a>
                </li>
                <li class="list-group-item">
                  <b>Following</b> <a class="pull-right">543</a>
                </li>
                <li class="list-group-item">
                  <b>Friends</b> <a class="pull-right">13,287</a>
                </li>
              </ul>
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

              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

              <p>
                <span class="label label-danger">UI Design</span>
                <span class="label label-success">Coding</span>
                <span class="label label-info">Javascript</span>
                <span class="label label-warning">PHP</span>
                <span class="label label-primary">Node.js</span>
              </p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
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
            </ul>

              <div class="tab-pane" id="settings">
                <form class="form-horizontal" method="post" action="/dashboard/profile-update" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                  <input type="hidden" name="u_id" value="{{ $user->id }}" />
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input class="form-control" id="inputName" name="name" value="{{ $user->name }}" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Title</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="{{ $settings->title }}" id="inputSkills" name="title" placeholder="Title">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="profile-image" class="col-sm-2 control-label">Profile image</label>

                    <div class="col-sm-10">
						          <input type="file" class="form-control" name="file_upload" />
                    </div>
                  </div>           
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail" name="email" value="{{ $user->email }}" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Education</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" name="education" placeholder="Education">{{ $settings->education }}</textarea>
                    </div>
                  </div>                   
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Location</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" name="location" placeholder="Location">{{ $settings->location }}</textarea>
                    </div>
                  </div>
                   <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" name="experience" placeholder="Experience">{{ $settings->experience }}</textarea>
                    </div>
                  </div>                 
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputSkills" name="skills" placeholder="Skills">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
    
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
  
@include('dashboard.includes.event-modal')
  
@endsection
