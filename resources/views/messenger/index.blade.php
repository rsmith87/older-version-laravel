@extends('adminlte::page')

@section('content')

  <div class="container dashboard messages home col-sm-12 offset-sm-2">

  @include('messenger.partials.flash')

  @include('dashboard.includes.alerts')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fas fa-quote-left"></i> Messages
        <small>{{ count($threads) }} new messages</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Messages</h3>

              <div class="box-tools pull-right">

              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->

          </div>
          <a class="btn btn-primary btn-block margin-bottom" data-toggle="modal" data-target="#create-message-modal">Compose</a>

          <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>

              <div class="box-tools pull-right">

              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            @if(count($threads) > 0)
            <div class="box-body no-padding">

              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                  @foreach($threads as $thread)
                    <tr class="thread">
                      <td><input type="checkbox" class="checkbox" /></td>
                      <td class="mailbox-subject"><b><a href="/dashboard/messages/{{ $thread->id }}">{{ $thread->subject }}</a></td>
                      <td class="mailbox-date">{{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}</td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            @else
            <p>No messages!</p>
            @endif
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  1 - {{ count($threads) }}/{{ count($threads) }}
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
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
            <div class="col-sm-12 col-xs-12">
              <div class="form-group">
              <label class="control-label">Subject</label>
              <input type="text" class="form-control" name="subject" placeholder="Subject"
                     value="{{ old('subject') }}">
              </div>
              <div class="form-group">
                <label>Send to email or user</label>
                <div class="clearfix"></div>
                <label><input type="radio" name="message_email_user" value="user" /> User</label>
                <label><input type="radio" name="message_email_user" value="email" />Email</label>
              </div>
              <div class="clearfix"></div>
              <div class="form-group email-selection">

                  <label>Email</label>
                  <input type="email" class="form-control" name="email_address" />

              </div>

              @if(count($users) > 0)
                <div class="form-group user-selection">
                    <label>Send to:</label>
                    <div class="checkbox">
                      @foreach($users as $u)
                        <div class="user-row-messages">
                          <label title="{{ $u[0]->name }}">
                            <input type="checkbox" name="recipients[]" value="{{ $u[0]['id'] }}">{!! $u[0]['name'] !!}</label>
                          <div class="clearfix"></div>
                        </div>
                      @endforeach
                    </div>
                </div>
              @endif

              <div class="clearfix"></div>
              <div class="form-group">
                <label class="control-label">Message</label>
                <textarea name="message" class="form-control">{{ old('message') }}</textarea>
              </div>
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
  <script type="text/javascript">





  </script>

@stop
