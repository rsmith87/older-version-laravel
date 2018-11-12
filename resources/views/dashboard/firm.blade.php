@extends('adminlte::page')

@section('content')

    <div class="container dashboard firm col-sm-12 offset-sm-2 scrollspy">
        <nav class="nav nav-pills">
            <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#edit-firm-modal" href="#"><i
                        class="fas fa-building"></i> Edit firm</a>
            <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#edit-firm-message-modal"
               href="#"><i class="fas fa-comment"></i> Edit firm message</a>
            <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#add-firm-user" href="#"><i
                        class="fas fa-user"></i> Add firm user</a>
        </nav>

        @include('dashboard.includes.alerts')


        <h1 class="mb-3 mt-4">
            @if(empty($firm->logo) ||  !isset($firm->logo) || $firm->logo === "")
                <i class="fas fa-address-card"></i> Firm Information
            @else
                <img src="/storage{{ $firm->logo}}"/>
            @endif
        </h1>


        <div class="col-sm-6 col-12">
            <label>Name</label>
            <p>{{ $firm['name'] }}</p>
            <label>Phone</label>
            <p>{{ $firm['phone'] }}</p>
            <label>Fax</label>
            <p>{{ $firm['fax'] }}</p>
            <label>Email</label>
            <p>{{ $firm['email'] }}</p>
        </div>
        <div class="col-sm-6 col-12">
            <label>Address</label>
            <p>{{ $firm['address_1'] }}<br/>
                {{ isset($firm['address_2']) ? $firm['address_2'] : "" }}<br/>
                {{ $firm['city'] }}, {{ $firm['state'] }} {{ $firm['zip'] }}</p>
            <label>Invoice Details</label>
            <p>{{ $firm['billing_details'] }}</p>
        </div>


    </div>


    <div class="modal fade" id="edit-firm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-building"></i> Edit firm information
                    </h3>

                    <div class="clearfix"></div>
                    <hr/>
                    <form class="form-horizontal" method="post" action="/dashboard/firm/add"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="firm_uuid" value="{{ isset($firm['uuid']) ? $firm['uuid'] : "" }}"/>

                        <div class="col-sm-6"><!-- Text input-->
                            <label class=" control-label" for="firm_name">Name</label>
                            <input id="firm_name" name="name" type="text" value="{{ $firm['name'] }}"
                                   placeholder="Firm Name" class="form-control input-md" required="true">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label class="control-label" for="firm-logo">Logo</label>
                            <input name="file_upload" id="firm-logo" type="file" class="form-control">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label for="firm_phone">Phone</label>
                            <input id="firm_phone" name="phone" type="text" value="{{ $firm['phone'] }}"
                                   placeholder="Phone" class="form-control input-md">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label for="firm_fax">Fax Number</label>
                            <input id="firm_fax" name="fax" type="text" value="{{ $firm['fax'] }}" placeholder="Fax"
                                   class="form-control input-md">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label for="firm_email">Email</label>
                            <input id="firm_email" name="email" type="text" value="{{ $firm['email'] }}"
                                   placeholder="Firm Email address" class="form-control input-md" required="true">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label>Address 1</label>
                            <input id="firm_address" name="address_1" type="text" value="{{ $firm['address_1'] }}"
                                   placeholder="Address Line 1" class="form-control input-md" required="true">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label for="firm_address">Address 2</label>
                            <input id="firm_address" name="address_2" type="text" value="{{ $firm['address_2'] }}"
                                   placeholder="Address Line 2" class="form-control input-md">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label for="firm_address">City</label>
                            <input id="firm_address" name="city" type="text" value="{{ $firm['city'] }}"
                                   placeholder="City" class="form-control input-md" required="true">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label for="firm_address">State</label>
                            <input id="firm_address" name="state" type="text" value="{{ $firm['state'] }}"
                                   placeholder="State" class="form-control input-md" required="true">
                        </div>

                        <div class="col-sm-6"><!-- Text input-->
                            <label for="firm_address">Zip</label>
                            <input id="firm_address" name="zip" type="text" value="{{ $firm['zip'] }}" placeholder="Zip"
                                   class="form-control input-md" required="true">
                        </div>

                        <div class="col-sm-12"><!-- Text input-->
                            <label class="control-label" for="billing-details">Billing details (ex. Days until invoice
                                are past due)</label>
                            <input id="billing-details" name="billing_details" type="text"
                                   value="{{ $firm['billing_details'] }}" placeholder="Billing details"
                                   class="form-control input-md">
                        </div>

                        <div class="col-12" id="firm-submit"><!-- Button -->
                            <button id="submit" name="submit" class="btn btn-primary btn-lg mt-3">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add-firm-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-user"></i> Add firm user
                    </h3>

                    <div class="clearfix"></div>
                    <hr/>

                    <form class="form-horizontal" method="post" action="/dashboard/firm/user/add">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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

    <hr/>
    <div class="col-sm-12">

        @if(isset($fs))
            @if(count($fs) > 0)
                <p>You have successfully authenticated Legalease and Stripe! If you'd like to authenticate again, or are
                    having issues with payments click the link below</p>
            @endif
        @else
            <a href="/dashboard/settings/stripe/create"><img src="{{ asset('img/blue-on-light.png') }}"/></a>
        @endif
    </div>
    <div class="clearfix"></div>
    <hr/>
    <div id="add-user" class="col-md-5 col-sm-12 box-shadow">

        <h2 class="pull-left ml-3 mt-3">
            <i class="fas fa-user"></i> Firm users
        </h2>

        <div class="clearfix"></div>

        @if (count($firm_staff) > 0)

            <table class="table table-responsive table-resposive table-striped table-hover" id="firm-users">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                </tr>
                </thead>
                <tbody>
                @foreach($firm_staff as $u)
                    <tr>
                        <td>{{ $u['id'] }}</td>
                        <td>{{ $u['name'] }}</td>
                        <td>{{ $u['email'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!--<div id="add-client-user" class="col-md-5 col-sm-12 box-shadow">
        <h2 class="pull-left ml-3 mt-3">
         <i class="fas fa-users"></i> Give a client login access
        </h2>
		<div class="clearfix"></div>

			<fieldset>
			<form class="form-horizontal" method="post" action="/dashboard/firm/user/client/add">
				 <input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="col-sm-12">
			<div class="form-group">
				<label  for="firm_name">Name</label>
				<div class="col-sm-12">
					 <input type="hidden" name="client_id" />
					 <input type="text" name="name" class="form-control" id="client_name" placeholder="Client name" />										
				</div>
			</div>
			</div>
			<div class="col-sm-12 mt-4">
			<div class="form-group">
				<div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success">Submit</button>
				</div>
			</div>
			</div>
				</form>
			 </fieldset>-->

    <!-- <h3><strong>Client Users</strong></h3>
			 @if(count($clients) > 0)
        <table class="table table-responsive table-resposive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
					<thead> 
						<tr>           
								<th scope="col">ID</th>
								<th scope="col">Name</th>
								<th scope="col">Email</th>
						</tr> 
					</thead> 
					<tbody> 
					@foreach($clients as $t)
            @if($user->has_login != 0)
                <tr>
                  <td>{{ $t->id }}</td>
						  <td>{{ $t->first_name }} {{ $t->last_name }}</td>
						  <td>{{ $t->email }}</td>
						</tr>
						@endif
        @endforeach
                </tbody>
            </table>
@endif
            </div>-->

    <div class="clearfix"></div>

    @foreach($firm_staff as $u)
        <div class="modal fade" id="edit-firm-user-{{ $u['id'] }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>
                            <i class="fas fa-building"></i> Edit firm user
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
    @endforeach



    <script src="{{ asset('js/autocomplete.js') }}"></script>
    <script type="text/javascript">
        var clients = {!! json_encode($clients->toArray()) !!};
        for (var i = 0; i < clients.length; i++) {
            clients[i].data = clients[i]['id'];
            clients[i].value = clients[i]['first_name'] + " " + clients[i]['last_name'];
        }

        $('input[id="client_name"]').autocomplete({
            lookup: clients,
            width: 'flex',
            triggerSelectOnValidInput: true,
            onSelect: function (suggestion) {
                var thehtml = '<strong>Case ' + suggestion.data + ':</strong> ' + suggestion.value + ' ';
                //alert(thehtml);
                var $this = $(this);
                $('#outputcontent').html(thehtml);
                $this.prev().val(suggestion.data);

            }

        });
    </script>

@endsection
