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
      <section class="content messaging-content">
        <div class="chat-container clearfix">
          <div class="people-list col-sm-2" id="people-list">
            <div class="search">
              <input type="text" placeholder="search" />
              <i class="fa fa-search"></i>
            </div>
            <ul class="list">

			  @foreach($firm_users as $f_u)
				<li class="clearfix">
				  <a href="#">
				  @if(Gravatar::exists($user->email))
					<img src="{{ Gravatar::get($user->email) }}" alt="avatar" />
				  @else
				  	<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01.jpg" alt="avatar" />
				  @endif
				  <div class="about">
					<div class="name">{{ $f_u[0]->name }}</div>
					<div class="status">
					  <i class="fa fa-circle online"></i> online
					</div>
				  </div>
				  </a>
				</li>
			  @endforeach
              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_02.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Aiden Chavez</div>
                  <div class="status">
                    <i class="fa fa-circle offline"></i> left 7 mins ago
                  </div>
                </div>
              </li>

              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_03.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Mike Thomas</div>
                  <div class="status">
                    <i class="fa fa-circle online"></i> online
                  </div>
                </div>
              </li>

              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_04.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Erica Hughes</div>
                  <div class="status">
                    <i class="fa fa-circle online"></i> online
                  </div>
                </div>
              </li>

              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_05.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Ginger Johnston</div>
                  <div class="status">
                    <i class="fa fa-circle online"></i> online
                  </div>
                </div>
              </li>

              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_06.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Tracy Carpenter</div>
                  <div class="status">
                    <i class="fa fa-circle offline"></i> left 30 mins ago
                  </div>
                </div>
              </li>

              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_07.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Christian Kelly</div>
                  <div class="status">
                    <i class="fa fa-circle offline"></i> left 10 hours ago
                  </div>
                </div>
              </li>

              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_08.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Monica Ward</div>
                  <div class="status">
                    <i class="fa fa-circle online"></i> online
                  </div>
                </div>
              </li>

              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_09.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Dean Henry</div>
                  <div class="status">
                    <i class="fa fa-circle offline"></i> offline since Oct 28
                  </div>
                </div>
              </li>

              <li class="clearfix">
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_10.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">Peyton Mckinney</div>
                  <div class="status">
                    <i class="fa fa-circle online"></i> online
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <div class="chat col-sm-10">
            <div class="chat-header clearfix">
              <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar" />

              <div class="chat-about">
                <div class="chat-with">Chat with Vincent Porter</div>
                <div class="chat-num-messages">already 1 902 messages</div>
              </div>
              <i class="fa fa-star"></i>
            </div> <!-- end chat-header -->

            <div class="chat-history">
              <ul>
                <li class="clearfix">
                  <div class="message-data align-right">
                    <span class="message-data-time" >10:10 AM, Today</span> &nbsp; &nbsp;
                    <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>

                  </div>
                  <div class="message other-message float-right">
                    Hi Vincent, how are you? How is the project coming along?
                  </div>
                </li>

                <li>
                  <div class="message-data">
                    <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                    <span class="message-data-time">10:12 AM, Today</span>
                  </div>
                  <div class="message my-message">
                    Are we meeting today? Project has been already finished and I have results to show you.
                  </div>
                </li>

                <li class="clearfix">
                  <div class="message-data align-right">
                    <span class="message-data-time" >10:14 AM, Today</span> &nbsp; &nbsp;
                    <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>

                  </div>
                  <div class="message other-message float-right">
                    Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so? Have you faced any problems at the last phase of the project?
                  </div>
                </li>

                <li>
                  <div class="message-data">
                    <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                    <span class="message-data-time">10:20 AM, Today</span>
                  </div>
                  <div class="message my-message">
                    Actually everything was fine. I'm very excited to show this to our team.
                  </div>
                </li>

                <li>
                  <div class="message-data">
                    <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                    <span class="message-data-time">10:31 AM, Today</span>
                  </div>
                  <i class="fa fa-circle online"></i>
                  <i class="fa fa-circle online" style="color: #AED2A6"></i>
                  <i class="fa fa-circle online" style="color:#DAE9DA"></i>
                </li>

              </ul>

            </div> <!-- end chat-history -->

            <div class="chat-message clearfix">
              <textarea name="message-to-send" id="message-to-send" placeholder ="Type your message" rows="3"></textarea>

              <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
              <i class="fa fa-file-image-o"></i>

              <button>Send</button>

            </div> <!-- end chat-message -->

          </div> <!-- end chat -->

        </div> <!-- end container -->

        <script id="message-template" type="text/x-handlebars-template">
          <li class="clearfix">
            <div class="message-data align-right">
              <span class="message-data-time" >{{ time() }}, Today</span> &nbsp; &nbsp;
              <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>f
            </div>
            <div class="message other-message float-right">
              WHOA THIS IS A MESSAGE
            </div>
          </li>
        </script>

        <script id="message-response-template" type="text/x-handlebars-template">
          <li>
            <div class="message-data">
              <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
              <span class="message-data-time">{{time()}}, Today</span>
            </div>
            <div class="message my-message">
              THIS IS A RESPONSE TO THAT MESSAGE
            </div>
          </li>
        </script>

      </section>

</div>

<div class="modal" id="create-message-modal" href="#">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
    <h1>Create a new message</h1>
    <form action="{{ route('messages.store') }}" method="post">
        {{ csrf_field() }}
            <!-- Subject Form Input -->

            <!-- Message Form Input -->
            <div class="col-xs-12">
                <label class="control-label">Message</label>
                <textarea name="message" class="form-control">{{ old('message') }}</textarea>
            </div>

          <div class="col-xs-12">
            <label>Users</label>
>
          </div>
    
            <!-- Submit Form Input -->
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
     
      </form>
      
      </div>
    </div>
  </div>
</div>


@stop
