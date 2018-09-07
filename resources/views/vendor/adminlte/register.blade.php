@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'register-page')

@section('body')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg">Register for Legalkeeper</p>
            <form action="{{ url(config('adminlte.register_url', 'register')) }}" method="post">
                {!! csrf_field() !!}

                <div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                           placeholder="{{ trans('adminlte::adminlte.full_name') }}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{{ trans('adminlte::adminlte.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.retype_password') }}">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('address_1') ? 'has-error' : '' }}">
                    <input type="text" name="address_1" class="form-control"
                           placeholder="Address 1" value="{{ old('address_1') }}">
                    <span class="glyphicon glyphicons-address-book form-control-feedback"></span>
                    @if ($errors->has('address_1'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address_1') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('address_2') ? 'has-error' : '' }}">
                    <input type="text" name="address_2" placeholder="Address 2" class="form-control" value="{{ old('address_2') }}">
                    <span class="glyphicon glyphicons-address-book form-control-feedback"></span>
                    @if ($errors->has('address_2'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address_2') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('city') ? 'has-error' : '' }}">
                    <input type="text" name="city" placeholder="City" class="form-control" value="{{ old('city') }}">
                    <span class="glyphicon glyphicons-address-book form-control-feedback"></span>
                    @if ($errors->has('city'))
                        <span class="help-block">
                            <strong>{{ $errors->first('city') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('state') ? 'has-error' : '' }}">
                    <input type="text" name="state" placeholder="State" class="form-control" value="{{ old('state') }}">
                    <span class="glyphicon glyphicons-address-book form-control-feedback"></span>
                    @if ($errors->has('state'))
                        <span class="help-block">
                            <strong>{{ $errors->first('state') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('zip') ? 'has-error' : '' }}">
                    <input type="text" name="zip" placeholder="Zip" class="form-control" value="{{ old('zip') }}">
                    <span class="glyphicon glyphicons-address-book form-control-feedback"></span>
                    @if ($errors->has('zip'))
                        <span class="help-block">
                            <strong>{{ $errors->first('zip') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('timezone_register') ? ' has-error' : '' }}">
           

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
                                print '<select class="form-control" name="timezone_register" id="timezone">
                                         <option value="choose" selected="selected">Choose timezone</option>';
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
                

                <button type="submit"
                        class="btn btn-primary btn-submit btn-block btn-flat"
                >{{ trans('Continue to payment details') }}<i class="fas fa-arrow-right"></i></button>
            </form>
            
            
            <div class="auth-links">
                <a href="{{ url(config('adminlte.login_url', 'login')) }}"
                   class="text-center">Login</a>
            </div>
        </div>
        <!-- /.form-box -->
    </div><!-- /.register-box -->
@stop

@section('adminlte_js')
    @yield('js')
@stop
