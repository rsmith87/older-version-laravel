@extends('adminlte::page')

@section('content')

<div class="container dashboard messages home col-sm-12 offset-sm-2">

	@include('messenger.partials.flash')
	
	@include('dashboard.includes.alerts') 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fas fa-quote-left"></i> Messages
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Messages</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a class="btn btn-primary btn-block margin-bottom" data-toggle="modal" data-target="#create-message-modal">Compose</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">{{ count($threads) }}</span></a></li>
                <li><a href="#"><i class="far fa-share-square"></i> Threads</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Search messages">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
                <div class="pull-right">
                  1- {{ count($threads) }}/{{ count($threads) }}
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                  @foreach($threads as $thread)
                  <tr class="thread">
                    <td><input type="checkbox" class="checkbox" /></td>
                    <td class="mailbox-subject"><b><a href="/dashboard/messages/{{ $thread->thread_uuid }}">{{ $thread->subject }}</a></td>
                    <td class="mailbox-date">{{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}</td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->

          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
     
    
  </div>
  </section>

	<div class="col-sm-7 offset-sm-5 actual-message col-12">

	</div>

	<div class="clearfix"></div>
	<div class="col-12">
	</div>
</div>

<div class="modal" id="create-message-modal" href="#">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
    <h1>Create a new message</h1>
    <form action="{{ route('messages.store') }}" method="post">
        {{ csrf_field() }}
            <!-- Subject Form Input -->
            <div class="form-group">
                <label class="control-label">Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Subject"
                       value="{{ old('subject') }}">
            </div>


            <!-- Message Form Input -->
            <div class="form-group">
                <label class="control-label">Message</label>
                <textarea name="message" class="form-control">{{ old('message') }}</textarea>
            </div>

          <div class="form_group">
            <label>Users</label>
            @if(count($firm_users) > 0)
                <div class="checkbox">
                    @foreach($firm_users as $f_u)
                      @foreach($f_u->Firm as $u)
                        <label title="{{ !empty($u->name) ? $u->name : $u->email }}">
                          <input type="checkbox" name="recipients[]" value="{{ $u->id }}">{{ !empty($u->name) ? $u->name : $u->email }}</label>
                        <br />
                        @endforeach
                    @endforeach
                </div>
            @endif
          </div>
    
            <!-- Submit Form Input -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
     
      </form>
      
      </div>
    </div>
  </div>
</div>


@stop
