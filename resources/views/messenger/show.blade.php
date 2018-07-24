@extends('adminlte::page')

@section('content')

<div class="container dashboard messages home col-sm-12 offset-sm-2">

	@include('messenger.partials.flash')
	
	@include('dashboard.includes.alerts') 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fas fa-quote-left"></i> Message box
        <small>13 new messages</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Messages</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
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
                <li class="active"><a href="/dashboard/messages"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">{{ count($threads) }}</span></a></li>
                <li><a href="#"><i class="far fa-share-square"></i> Starred threads</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
    <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Read Message</h3>

              <div class="box-tools pull-right">
                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Previous"><i class="fa fa-chevron-left"></i></a>
                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Next"><i class="fa fa-chevron-right"></i></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3>{{ $thread->subject }}</h3>
                <h5>From: @foreach($users as $u) {{ $u[0]['name'] }} @endforeach
                  <span class="mailbox-read-time pull-right">15 Feb. 2016 11:03 PM</span></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border text-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Reply">
                    <i class="fa fa-reply"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="" data-original-title="Print">
                  <i class="fa fa-print"></i></button>
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                @if(count($message) > 0)
                  @foreach($message as $m)
                    <div class="media">
                    <img src="//www.gravatar.com/avatar/{{ md5($m->user->email) }} ?s=100" alt="{{ $m->user->name }}" class="img-circle">
                      <div class="media-body">
                          <h5 class="media-heading">{{ $m->user->name }}</h5>
                          <div class="text-muted">
                              <small>Sent {{ $m->created_at->diffForHumans() }}</small>
                          </div>
                          <p>{{ $m->body }}</p>
                      </div>
                  </div>
                  @endforeach
                @endif
                <h2>Add a new message</h2>
                <form action="{{ route('messages.update', $thread->id) }}" method="POST">
                    {{ csrf_field() }}

                    <!-- Message Form Input -->
                    <div class="form-group">
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
                        <button type="submit" id="message-update" class="btn btn-primary form-control">Submit</button>
                    </div>
                </form>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <!--<div class="box-footer">
              <ul class="mailbox-attachments clearfix">
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> Sep2014-report.pdf</a>
                        <span class="mailbox-attachment-size">
                          1,245 KB
                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
                
              </ul>
            </div>-->
            <!-- /.box-footer -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
              </div>
              <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
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
@endsection

