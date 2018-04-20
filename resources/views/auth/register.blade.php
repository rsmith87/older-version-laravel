@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                      
                          <div class="form-group{{ $errors->has('timezone') ? ' has-error' : '' }}">
           

                            <div class="col-md-6">
                              @php
                                $regions = array(
                                    //'Africa' => DateTimeZone::AFRICA,
                                    'America' => DateTimeZone::AMERICA,
                                    //'Antarctica' => DateTimeZone::ANTARCTICA,
                                    //'Asia' => DateTimeZone::ASIA,
                                    //'Atlantic' => DateTimeZone::ATLANTIC,
                                    //'Europe' => DateTimeZone::EUROPE,
                                    //'Indian' => DateTimeZone::INDIAN,
                                    //'Pacific' => DateTimeZone::PACIFIC
                                );
                                $timezones = array();
                                foreach ($regions as $name => $mask)
                                {
                                    $zones = DateTimeZone::listIdentifiers($mask);
                                    foreach($zones as $timezone)
                                    {
                                    // Lets sample the time there right now
                                    $time = new DateTime(NULL, new DateTimeZone($timezone));
                                    // Us dumb Americans can't handle millitary time
                                    $ampm = $time->format('H') > 12 ? ' ('. $time->format('g:i a'). ')' : '';
                                    // Remove region name and add a sample time
                                    $timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' - ' . $time->format('H:i') . $ampm;
                                  }
                                }
                                // View
                                print '<label class="col-md-4 control-label">Select Your Timezone</label>
                                       <select class="form-control" name="timezone_register" id="timezone">';
                                foreach($timezones as $region => $list)
                                {
                                  //print '<optgroup label="' . $region . '">' . "\n";
                                  foreach($list as $timezone => $name)
                                  {
                                    print '<option value="' . $timezone . '">' . $name . '</option>' . "\n";
                                  }
                                  //print '<optgroup>' . "\n";
                                }
                                print '</select>';
                              @endphp
                         

                                @if ($errors->has('timezone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('timezone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                    
                      
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
