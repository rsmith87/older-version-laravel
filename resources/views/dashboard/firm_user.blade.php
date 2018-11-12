@extends('adminlte::page')

@section('content')

    <div class="container dashboard firm col-sm-12 offset-sm-2 scrollspy">
        <nav class="nav nav-pills">
            <a class="nav-item nav-link btn btn-info" href="/dashboard/firm"><i
                        class="fas fa-backward"></i> Back to firm</a>
            <a class="nav-item nav-link btn btn-danger" data-toggle="modal" data-target="#cancel-user" href="#"><i
                        class="fas fa-user"></i> Cancel user subscription</a>
        </nav>

        @include('dashboard.includes.alerts')


        <h1 class="mb-3 mt-4">
            @if(empty($firm->logo) ||  !isset($firm->logo) || $firm->logo === "")
                <i class="fas fa-address-card"></i> User Information
            @else
                <img src="/storage{{ $firm->logo}}"/>
            @endif
        </h1>


        <div class="col-sm-6 col-xs-12">
            <h3>User:</h3>
            <label>Name</label>
            <p>{{ $user['name'] }}</p>
            <label>Email</label>
            <p>{{ $user['email'] }}</p>
            <label>Created date</label>
            <p>{{ \Carbon\Carbon::parse($user['created_at'])->format('m/d/Y g:i A') }}</p>
        </div>

        <div class="col-sm-6 col-xs-12">
            <h3>Settings:</h3>
            @if($settings->education != "")
                <label>Education</label>
                <p>{{ $settings->education }}</p>
            @endif

            @if($settings->experience != "")
                <label>Experience</label>
                <p>{{ $settings->experience }}</p>
            @endif

            @if($settings->location != "")
                <label>Location</label>
                <p>{{ $settings->location }}</p>
            @endif

            @if($settings->focus != "")
                <label>Focus</label>
                <p>{{ $settings->focus }}</p>
            @endif

            @if($settings->title != "")
                <label>Title</label>
                <p>{{ $settings->title }}</p>
            @endif

            @if($settings->state_of_bar != "")
                <label>State of bar</label>
                <p>{{ $settings->state_of_bar }}</p>
            @endif

            @if($settings->bar_number != "")
                <label>Bar number</label>
                <p>{{ $settings->bar_number }}</p>
            @endif
        </div>



    </div>




    <div class="modal fade" id="cancel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-user"></i> Cancel user subscription
                    </h3>

                    <div class="clearfix"></div>
                    <hr/>
                        <fieldset>
                            <form class="form-horizontal" method="post" action="/dashboard/firm/user/cancel">

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="user_id" value="{{ $user['id'] }}" />
                                <div class="col-sm-12"><!-- Text input-->
                                    <div class="form-group">
                                        <label for="firm_name">Name</label>
                                        <div class="col-sm-12">
                                            <input id="new_user_name" name="name" type="text" placeholder="First name"
                                                   class="form-control input-md" required="true">
                                        </div>
                                    </div>
                                </div>

                                <div class='col-sm-12'>
                                    <div class="form-group">
                                        <label for="firm_name">Email address</label>
                                        <div class="col-sm-12">
                                            <input id="new_user_email" name="email" type="text"
                                                   placeholder="Email address"
                                                   class="form-control input-md" required="true">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 mt-4"><!-- Button -->
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <button id="submit" name="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </fieldset>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-firm-message-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-building"></i> Edit firm message
                    </h3>

                    <div class="clearfix"></div>
                    <hr/>
                    <form class="form-horizontal" method="post" action="/dashboard/firm/message/add">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="firm_id" value="{{ $firm['id'] }}"/>


                        <label class=" control-label" for="firm_name">Message</label>
                        <textarea name="firm_message"
                                  class="form-control">{{ isset($message) ? $message->firm_message : "" }}</textarea>


                        <button id="submit" name="submit" class="btn btn-primary">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </div>





@endsection
