@extends('adminlte::page')

@section('content')

    <div class="container dashboard case col-sm-12 col-12 offset-sm-2">

        @include('dashboard.type_navigation.case')
        @include('dashboard.includes.alerts')
        @if(count($firm_stripe) <1 )
            <div class="alert alert-warning fade in ml-3 mr-3 mb-4" role="alert'">
                Your firm has not completed the Stripe signup to connect payments. You will be unable to invoice clients
                until this is complete.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div data-spy="scroll" class="scroll-spy" data-target="#mynav">
            <h1 class="pull-left ml-3 mt-4 mb-2">
                <i class="fas fa-balance-scale"></i> Case
            </h1>
            <div class="clearfix"></div>
            <p class="ml-3 mb-4">
                Case information for {{ $case->name }}
            </p>

            <div class="col-xs-12" data-spy="affix" data-offset="180">
                <div class="list-group navbar" id="sidebar">
                    <ul class="nav nav-pills" id="mynav">
                        <li><a href="#case-information" class="list-group-item">Case Information</a></li>
                        <li><a href="#timers-and-hours" class="list-group-item">Timers and worked hours</a></li>
                        <li><a href="#notes" class="list-group-item">Notes</a></li>
                        <li><a href="#contacts" class="list-group-item">Contacts</a></li>
                        <li><a href="#calendar" class="list-group-item">Events</a></li>
                        <li><a href="#media-and-documents" class="list-group-item">Media and Documents</a></li>
                        <li><a href="#invoices" class="list-group-item">Invoices</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-xs-12">
                <div id="case-information">
                    <div class="col-sm-6 col-xs-12">
                        @if($case->status)
                            <label>Status</label>
                            <p>{{ ucfirst($case->status) }}</p>
                        @endif

                        @if($case->type)
                            <label>Type</label>
                            <p>{{ str_replace('_', ' ', ucfirst($case->type)) }}</p>
                        @endif

                        @if($case->name)
                            <label>Name</label>
                            <p>{{ $case->name }}</p>
                        @endif

                        @if($case->number)
                            <label>Case number</label>
                            <p>{{ $case->number }}</p>
                        @endif

                        @if($case->description)
                            <label>Case description</label>
                            <p>{{ $case->description }}</p>
                        @endif

                        @if($case->court_name)
                            <label>Court name</label>
                            <p>{{ $case->court_name }}</p>
                        @endif

                        @if($case->opposing_councel)
                            <label>Opposing Councel</label>
                            <p>{{ $case->opposing_councel }}</p>
                        @endif

                        @if($case->location)
                            <label>Location</label>
                            <p>{{ $case->location }}</p>
                        @endif
                    </div>

                    <div class="col-sm-6 col-xs-12" id="client">
                        @if(count($client) > 0)
                            <label>Client</label>
                            <p>
                                <a class="nav-item nav-link btn btn-sm btn-success" data-toggle="modal"
                                   data-target="#view-client-modal-full"
                                   href="/dashboard/clients/client/{{ $client->contlient_uuid }}#contact-information"><i
                                            class="fas fa-user"></i> {{ $client->first_name }} {{ $client->last_name }}
                                </a>
                            </p>
                        @endif
                        <label>Created</label>
                        <p>{{ \Carbon\Carbon::parse($case->created_at)->format('m/d/Y g:i A') }}</p>
                        <label>Total cost</label>
                        @if($case->billing_type === 'hourly')
                            @if($case->billing_rate === "")
                                <p>N/A</p>
                            @else
                                <p>${{ number_format($hours_worked * $case->billing_rate, 2) }}</p>
                            @endif
                        @else
                            @if($case->billing_rate === "")
                                <p>N/A</p>
                            @else
                                <p>${{ (float) number_format($case->billing_rate, 2) }}</p>
                            @endif
                        @endif

                        @if($case->claim_reference_number)
                            <label>Claim reference number</label>
                            <p>{{ $case->claim_reference_number }}</p>
                        @endif

                        @if($case->statute_of_limitations)
                            <label>Statute of Limitations</label>
                            <p>{{ $case->statute_of_limitations ? "Complete" : "Not complete" }}</p>
                        @endif

                        @if($case->open_date != "0000-00-00 00:00:00")
                            <label>Open date</label>
                            <p>{{ \Carbon\Carbon::parse($case->open_date)->format('m/d/Y') }}</p>
                        @endif

                        @if($case->close_date != "0000-00-00 00:00:00")
                            <label>Close date</label>
                            <p>{{ \Carbon\Carbon::parse($case->close_date)->format('m/d/Y') }}</p>
                        @endif

                        @if($case->billing_rate)
                            <label>Rate</label>
                            <p>${{ $case->billing_rate }}</p>
                        @endif

                        @if($hours_worked)
                            <label>Hours</label>
                            <p>{{ $hours_worked }} hours</p>
                        @endif

                        @if($case->billing_type)
                            <label>Rate type</label>
                            <p>{{ ucfirst($case->billing_type) }}</p>
                        @endif

                    </div>
                </div>

                @if(count($full_case_hours) > 0)
                    <div class="col-xs-12" id="timers-and-hours">
                        <h3 class="mt-5 ml-3">
                            <i class="fas fa-clock"></i> Hours and Timers
                        </h3>
                        <div class="clearfix"></div>
                        <div class="mb-3 ml-3" style="overflow:hidden;">
                            @for($i=0;$i<count($full_case_hours);$i++)
                                <div>
                                    <div class="card-body">
                                        <a class="pull-right" data-toggle="modal"
                                           data-target="#delete-hours-modal-{{ $full_case_hours[$i]['id'] }}"><i
                                                    class="fas fa-trash-alt"></i></a>
                                        @if($full_case_hours[$i]['type'] != 'timer')
                                            <a data-toggle="modal" class="pull-right"
                                               data-target="#edit-hours-modal-{{ $full_case_hours[$i]['id'] }}"><i
                                                        class="fas fa-edit"></i></a>
                                        @endif
                                        <h5 class="card-title">
                                            Created: {{ \Carbon\Carbon::parse($full_case_hours[$i]['created_at'])->format('m/d/Y g:i A') }}</h5>
                                        <p class="card-text">
                                            <strong>Hours</strong>: {{ round((float) $full_case_hours[$i]['time']/3600, 2) }}
                                            <br/> <strong>Note</strong>: {{ $full_case_hours[$i]['description'] }}</p>
                                    </div>
                                </div>
                            @endfor

                        </div>
                    </div>
                @endif

                @if(count($notes) > 0)
                    <div class="col-xs-12" id="notes">
                        <h3 class="mt-5 ml-3">
                            <i class="fas fa-sticky-note"></i> Notes
                        </h3>
                        <div class="clearfix"></div>
                        <div class="mb-3 ml-3" style="overflow:hidden;">


                            @foreach($notes as $note)
                                <div>

                                    <div class="card-body">
                                        <a class="pull-right" data-toggle="modal"
                                           data-target="#delete-note-modal-{{ $note->id }}"><i
                                                    class="fas fa-trash-alt"></i></a>
                                        <a data-toggle="modal" class="pull-right"
                                           data-target="#edit-note-modal-{{ $note->id }}"><i
                                                    class="fas fa-edit"></i></a>
                                        <h5 class="card-title">
                                            Created: {{ \Carbon\Carbon::parse($note->created_at)->format('m/d/Y g:i A') }}</h5>
                                        <p class="card-text">{{ $note->note }}</p>
                                    </div>
                                </div>


                            @endforeach
                        </div>
                    </div>
                @endif


                @if(!empty($contacts))
                    @foreach($contacts as $contact)
                        @if($contact->is_client != 1)
                            <div class="col-xs-12 contacts-case" id="contacts">
                                <div class="clearfix"></div>
                                <h3 class="mt-5">
                                    <i class="fa fa-address-card"></i> Contacts
                                </h3>
                                <table id="contacts"
                                       class="table table-{{ $table_size }} table-hover table-responsive table-striped table-{{ $table_color }} mb-3">
                                    <thead>
                                    <tr class="bg-primary">
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                        <th>Relationship</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $contact->contlient_uuid }}</td>
                                        <td>{{ $contact->first_name }} {{ $contact->last_name }}</td>
                                        <td>{{ !empty($contact->phone) ? $contact->phone : "Not set" }}</td>
                                        <td>{{ !empty($contact->email) ? $contact->email : "Not set" }}</td>
                                        <td>{{ !empty($contact->relationship) ? $contact->relationship : "Not set" }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach
                @endif

                @if(!empty($events))

                    @if(count($events) > 0)
                        <div id="calendar" class="col-xs-12 col-sm-6 fc fc-unthemed fc-ltr"></div>

                    @endif
                @endif


                @if(!empty($media))
                    @if(count($media) > 0)
                        <div class="col-xs-12" id="media-and-documents">
                            <h3 class="mt-5">
                                <i class="fa fa-file"></i> Media and Documents
                            </h3>
                            @foreach($media as $m)

                                <div class="col-sm-4 col-xs-12 col-md-4 case-document">
                                    @if($m[0]->mime_type === 'png' || $m[0]->mime_type === 'jpg' || $m[0]->mime_type === 'gif')
                                        <img src="{{ env('HTTP_TYPE') }}://{{ env('APP_DOMAIN') . '/files/user/'.\Auth::id(). '/' . $m[0]->name }}"/>
                                    @elseif($m[0]->mime_type==='docx')
                                        <p>Name: {{ $m[0]->file_name }}</p>
                                        <p>Size: {{ round($m[0]->size / 1000, 2) }} KB</p>
                                    @endif

                                    <a data-toggle="modal" class="btn btn-block btn-primary"
                                       data-target='#view-media-modal-{{ $m[0]->uuid }}' href='#'>View</a>
                                    <a data-toggle="modal" class="btn btn-block btn-danger"
                                       data-target='#delete-media-modal-{{ $m[0]->uuid }}' href='#'>Delete</a>

                                </div>
                                @endforeach
                                </tbody>
                                </table>
                        </div>
                    @endif
                @endif

                @if(!empty($invoices))
                    @if(count($invoices) > 0)
                        <div class="col-xs-12" id="invoices">
                            <h3 class="mt-5">
                                <i class="fa fa-file"></i> Invoices
                            </h3>
                            @foreach($invoices as $invoice)
                                <div class="invoice-small invoice-small-case col-md-3 col-sm-5 col-xs-12 box-shadow box-white">
                                    @if($invoice->paid)
                                        <p class="text-green text-center text-bold">PAID</p>
                                    @else
                                        <p class="text-center text-red text-bold">UNPAID</p>
                                    @endif
                                    <label>To</label>
                                    <p>{{ $invoice->receiver_info }}</p>
                                    <p>{{ $invoice->sender_info }}</p>
                                    <label>Due</label>
                                    <p>{{ \Carbon\Carbon::parse($invoice->due_date)->format('m/d/Y g:i A') }}</p>
                                    <label>Description</label>
                                    <p>{{ $invoice->description }}</p>
                                    <label>Total</label>
                                    <p>$ {{ $invoice->total }}.00</p>
                                    <a class="btn btn-primary btn-block"
                                       href="/dashboard/invoices/invoice/{{$invoice->invoice_uuid}}">View</a>
                                    <a class="btn btn-danger btn-block"
                                       href="/dashboard/invoices/invoice/{{$invoice->invoice_uuid}}/delete">Delete</a>
                                </div>
                                @endforeach
                                </tbody>
                                </table>
                        </div>
                    @endif
                @endif

                @if(count($task_lists) > 0)
                    @foreach($task_lists as $task_list)
                        <div class="col-xs-12" id="tasklists">
                            <h3 class="mt-5">
                                <i class="fa fa-clipboard-list"></i> Task list {{ $task_list->task_list_name }}
                            </h3>
                            <table id="tasks"
                                   class="table table-{{ $table_size }} table-responsive table-striped table-{{ $table_color }}">
                                <thead>
                                <tr class="bg-primary">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($task_list->Task as $task)
                                    <tr>
                                        <td>{{ $task_list->id }}</td>
                                        <td>{{ $task->task_name }}</td>
                                        <td>{{ $task->task_description }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endif


            </div>
        </div>
    </div>

    @include('dashboard.includes.event-modal')
    @include('dashboard.includes.client-modal')
    @include('dashboard.includes.contact-modal')
    @include('dashboard.includes.case-modal')


    @if(!empty($media))
        @if(count($media) > 0)
            @foreach($media as $m)
                <div class="modal fade" id="delete-media-modal-{{ $m[0]->uuid }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h3>
                                    <i class="fas fa-sticky-note"></i> Delete media
                                </h3>
                                <div class="clearfix"></div>
                                <hr/>
                                <form method="POST" action="/dashboard/documents/document/delete">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                    <input type="hidden" name="media_id" value="{{ $m[0]->uuid }}"/>
                                    <input type="hidden" name="media_name" value="{{ $m[0]->name }}"
                                    <p>
                                        {{ $m[0]->name }}
                                    </p>
                                    <p>
                                        <img src="{{ $m[0]->path }}"/>
                                    </p>
                                    <p>
                                        Warning: This is permanent! If you'd like to delete this document/media, click
                                        delete below!
                                    </p>
                                    <input type="submit" class="form-control mt-3 btn btn-danger" value="Delete "/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            @endforeach
        @endif
    @endif

    <div class="modal fade" id="add-notes-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-sticky-note"></i> Add Note
                    </h3>
                    <div class="clearfix"></div>
                    <hr/>
                    <form method="POST" action="/dashboard/cases/case/notes/note/add">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="case_uuid" value="{{ $case->case_uuid }}"/>
                        <label>Note</label>
                        <textarea name="note" class="form-control"></textarea>
                        <button type="submit" class="form-control mt-3 btn btn-primary">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-trash-alt"></i> Delete case
                    </h3>
                    <div class="clearfix"></div>
                    <hr/>
                    <form method="POST" action="/dashboard/cases/case/delete">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="case_uuid" value="{{ $case->case_uuid }}"/>
                        <p>Click the button below to confirm the case deletion.</p>
                        <button type="submit" class="form-control mt-3 btn btn-danger">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="document-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-cloud-upload-alt"></i> Upload document
                    </h3>
                    <div class="clearfix"></div>
                    <hr/>
                    <form method="post" action="/dashboard/documents/case/upload" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="uuid" value="{{ $case->case_uuid }}">
                        <div class="col-xs-12">
                            <label for="file_upload">File</label>
                            <input type="file" class="form-control" name="file_upload"/>
                            <p>Documents added through case page are automatically related to the case.</p>


                            <button class="btn btn-primary mt-3 mb-3" type="submit">
                                <i class="fas fa-check"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="change-client-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-user"></i> Change client
                    </h3>
                    <div class="clearfix"></div>
                    <hr/>
                    <form method="POST" action="/dashboard/cases/client/update">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="case_id" value="{{ $case->id }}"/>
                        <input type="hidden" name="case_uuid" value="{{ $case->case_uuid }}"/>
                        @if(count($case->Contacts) > 0)
                            @foreach($case->Contacts as $contact)
                                @if($contact->is_client)
                                    <input type="hidden" name="old_client" value="{{ $contact->contlient_uuid }}"/>
                                @endif
                            @endforeach
                        @endif
                        <label>Client</label>
                        <input type="hidden" name="client_id"/>
                        <select name="client_update" class="form-control">
                            @foreach($clients as $t)
                                <option value="{{ $t->contlient_uuid }}" {{ $t->id == $case->Client->id ? "selected='selected'" : '' }}>{{ ucwords($t->first_name) . " " . ucwords($t->last_name) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="form-control mt-3 btn btn-primary">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reference-modal-full">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-sticky-note"></i> Reference client to case
                    </h3>
                    <div class="clearfix"></div>
                    <hr/>
                    <form method="POST" action="/dashboard/cases/case/reference">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="case_id" value="{{ $case->id }}"/>
                        <input type="hidden" name="case_uuid" value="{{ $case->case_uuid }}"/>
                        <label>Client</label>
                        <input type="hidden" name="client_id"/>
                        <input type="text" name="client_name" id="client_name" class="form-control"/>
                        <button type="submit" class="form-control mt-3 btn btn-primary">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-hours-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-tasks"></i> Add hours to case
                    </h3>
                    <div class="clearfix"></div>
                    <hr/>
                    <form role="form" method="post" action="/dashboard/cases/case/add-hours">
                        <input type="hidden" name="case_id" value="{{ $case->id }}"/>
                        <input type="hidden" name="case_uuid" value="{{ $case->case_uuid }}"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-12">
                            <label>Amount</label>
                            <input type="text" class="form-control" name="hours_worked"/>
                        </div>
                        <div class="col-12">
                            <label>Note</label>
                            <textarea name="hours_note" class="form-control"></textarea>
                        </div>
                        <div class="col-12">
                            <input type="submit" class="btn btn-primary mt-2 form-control"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tasklist-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-tasks"></i> Add taskboard
                    </h3>
                    <p>(Note: This will redirect you to the taskboard page to add tasks)</p>
                    <div class="clearfix"></div>
                    <hr/>
                    <form role="form" method="post" action="/dashboard/cases/case/create-tasklist">
                        <input type="hidden" name="case_id" value="{{ $case->id }}"/>
                        <input type="hidden" name="case_uuid" value="{{ $case->case_uuid }}"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-xs-12">
                            <label>Name</label>
                            <input type="text" class="form-control" name="tasklist_name"/>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label>Due date</label>
                            <input type="text" name="tasklist_due_date" class="form-control dp"/>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label>Time due</label>
                            <input type="text" name="tasklist_due_time" class="form-control timepicker-end"/>
                        </div>
                        <div class="col-xs-12">
                            <input type="submit" class="btn btn-primary mt-2 form-control"/>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(count($client) > 0)
        <div class="modal fade" id="view-client-modal-full">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>
                            <i class="fas fa-user"></i> View Client
                        </h3>
                        <div class="clearfix"></div>
                        <hr/>
                        <div class="col-sm-6 col-xs-12">
                            <label>Name</label>
                            <p>{{ $client->first_name }} {{ $client->last_name }}</p>
                            <p>
                                <a class="btn btn-primary" href="tel:{{ $client->phone }}"><i class="fas fa-phone"></i>
                                    Call</a>
                                <a class="btn btn-primary" href="mailto:{{ $client->email }}"><i
                                            class="fas fa-envelope"></i> Email</a>
                                <a class="nav-item nav-link btn btn-primary" data-toggle="modal"
                                   data-target="#payment-modal-full" href="#"><i
                                            class="fas fa-dollar-sign"></i> Bill</a>
                            </p>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <label>Address</label>

                            <p>
                                <a href="#" class="mapThis"
                                   place="{{ $client->address_1 }} {{ $client->address_2 }} {{ $client->city }} {{ $client->state }} {{ $client->zip }}"
                                   zoom="16">{{ $client->address_1 }}
                                    {{ $client->address_2 }}<br/>
                                    {{ $client->city }}, {{ $client->state }} {{ $client->zip }}
                                </a>
                            </p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12">
                            <a class="btn btn-success" href="/dashboard/clients/client/{{ $client->contlient_uuid }}">View {{ $client->first_name }} {{ $client->last_name }}
                                's page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="case-edit-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-briefcase"></i> Edit case
                    </h3>
                    <div class="clearfix"></div>
                    <hr/>
                    <form role="form" method="post" action="/dashboard/cases/create">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $case->id }}">
                        <input type="hidden" name="u_id" value="{{ $user['id'] }}"/>
                        <input type="hidden" name="user_id" value="{{ $user['id'] }}"/>

                        <div class="col-sm-6 col-xs-12">


                            <label for="inputGroupSelect01">Status</label>

                            <select class="form-control" name="status" id="inputGroupSelect01" aria-label="Status"
                                    aria-describedby="inputGroup-sizing-sm">
                                @foreach($status_values as $t)
                                    <option value="{{ $t }}" {{ $t == $case->status ? "selected='selected'" : '' }}>{{ ucwords($t) }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-sm-6 col-xs-12">


                            <label for="inputGroupSelect01">Type</label>

                            <select class="form-control" name="type" id="inputGroupSelect01" aria-label="Type"
                                    aria-describedby="inputGroup-sizing-sm">
                                @foreach($case_types as $t)
                                    <option value="{{ $t }}" {{ $t == $case->type ? "selected='selected'" : '' }}>{{ str_replace('_', ' ', ucwords($t)) }}</option>
                                @endforeach
                            </select>

                        </div>


                        <div class="col-sm-12 col-xs-12">


                            <label>Name</label>
                            <input type="text" class="form-control" id="name" value="{{ $case->name }}" name="name"
                                   aria-label="Name">
                        </div>
                        <div class="col-sm-12">


                            <label>Description</label>
                            <textarea class="form-control" aria-label="Description" name="description"
                                      id="description">{{ $case->description }}</textarea>
                        </div>
                        <div class="col-sm-6 col-xs-12">


                            <label>Case Number</label>
                            <input type="text" class="form-control" id="case_number" value="{{ $case->number }}"
                                   name="case_number"
                                   aria-label="Case Number">
                        </div>
                        <div class="col-sm-6 col-xs-12">


                            <label>Court name</label>
                            <input type="text" class="form-control" id="court_name" value="{{ $case->court_name }}"
                                   name="court_name"
                                   aria-label="Court name">
                        </div>
                        <div class="col-sm-6 col-xs-12">


                            <label>Opposing Councel</label>

                            <input type="text" class="form-control" id="opposing_councel"
                                   value="{{ $case->opposing_councel }}"
                                   name="opposing_councel" aria-label="Opposing Councel">
                        </div>
                        <div class="col-sm-6 col-xs-12">


                            <label>Location</label>
                            <input type="text" class="form-control" id="location" value="{{ $case->location }}"
                                   name="location"
                                   aria-label="Location">
                        </div>
                        <div class="col-sm-6 col-xs-12">


                            <label>Claim Reference Number</label>
                            <input type="text" class="form-control" id="claim_reference_number"
                                   value="{{ $case->claim_reference_number }}" name="claim_reference_number"
                                   aria-label="Claim reference number">
                        </div>
                        <div class="col-sm-6 col-xs-12">


                            <label>Open date</label>
                            <input type="text" class="form-control dp"
                                   value="{{ $case->open_date != "0000-00-00 00:00:00" ? \Carbon\Carbon::parse($case->open_date)->format('m/d/Y'): "" }}"
                                   id="open_date"
                                   name="open_date" aria-label="Open date">
                        </div>
                        <div class="col-sm-6 col-xs-12">


                            <label>Close date</label>
                            <input type="text" class="form-control dp" id="close_date"
                                   value="{{ $case->close_date != "0000-00-00 00:00:00" ? \Carbon\Carbon::parse($case->close_date)->format('m/d/Y') : ""}}"
                                   name="close_date" aria-label="Close date">
                        </div>

                        <div class="col-sm-6 col-xs-12">


                            <label>Rate</label>
                            <input type="text" class="form-control" name="billing_rate"
                                   value="{{ $case->billing_rate }}"
                                   aria-label="Amount (to the nearest dollar)">

                        </div>


                        <div class="col-sm-6 col-xs-12">
                            <label>Hours</label>
                            <input type="text" class="form-control" name="hours" value="{{ $hours_worked }}"
                                   aria-label="Hours worked">
                        </div>
                        <div class="col-sm-6 col-xs-12 mt-4">
                            @php
                                $types = ['fixed', 'hourly'];
                            @endphp
                            @foreach($types as $type)
                                <label>{{ ucfirst($type) . " rate" }}</label>
                                <input type="radio" name="rate_type"
                                       value='{{ $type }}' {{ $case->billing_type === $type ? 'checked=checked' : '' }} />
                            @endforeach
                        </div>
                        <div class="col-sm-6 col-xs-12 mt-4">


                            <label>Statute of Limitations</label>

                            <input type="checkbox"
                                   {{ !empty($case->statute_of_limitations) ? "checked" : '' }} name="statute_of_limitations"
                                   aria-label="Statute of Limitations">


                        </div>

                        <div class="col-sm-12 col-xs-12">

                            <button class="btn btn-primary mt-3"><i class="fas fa-check"></i> Submit</button>


                        </div>

                        <div class="clearfix"></div>
                        <input type="hidden" name="id" value="{{ $case->id }}">

                    </form>
                </div>
            </div>
        </div>
    </div>



    @foreach($notes as $note)
        <div class="modal fade" id="edit-note-modal-{{ $note->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>
                            <i class="fas fa-tasks"></i> Edit note
                        </h3>
                        <div class="clearfix"></div>
                        <hr/>
                        <form method="POST" action="/dashboard/cases/case/note/edit">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="hidden" name="id" value="{{ $note->id }}"/>
                            <div class="col-xs-12">
                                <label>Note</label>
                                <input type="text" class='form-control' name="note" value="{{ $note->note }}"/>
                            </div>
                            <div class="col-xs-12">
                                <input type="submit" class="form-control mt-3 btn btn-primary" value="Save"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete-note-modal-{{ $note->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>
                            <i class="fas fa-tasks"></i> Delete note
                        </h3>
                        <div class="clearfix"></div>
                        <hr/>
                        <form method="POST" action="/dashboard/cases/case/note/delete">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="hidden" name="id" value="{{ $note->id }}"/>
                            <p>
                                {{ $note->note }}
                            </p>
                            <p>
                                Warning: This is permanent! If you'd like to delete this note, click delete below!
                            </p>
                            <input type="submit" class="form-control mt-3 btn btn-danger" value="Delete "/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if(count($full_case_hours) > 0)
        @for($i=0;$i<count($full_case_hours);$i++)

            <div class="modal fade" id="edit-hours-modal-{{ $full_case_hours[$i]['id'] }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h3>
                                <i class="fas fa-clock"></i> Edit hours
                            </h3>
                            <div class="clearfix"></div>
                            <hr/>
                            <form method="POST" action="/dashboard/cases/case/hours/edit">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="type" value="{{ $full_case_hours[$i]['type'] }}"/>
                                <input type="hidden" name="id" value="{{ $full_case_hours[$i]['id'] }}"/>
                                <div class="col-xs-12">
                                    <label>Hours</label>
                                    <input type="text" class="form-control" name="hours"
                                           value="{{ $full_case_hours[$i]['readable'] }}"/>
                                </div>
                                <div class="col-xs-12">
                                    <label>Note</label>
                                    <input type="text"
                                           {{ $full_case_hours[$i]['type'] === 'timer' ? 'disabled="disabled"' : '' }} class='form-control'
                                           name="note" value="{{ $full_case_hours[$i]['description'] }}"/>
                                </div>
                                <div class="col-xs-12">
                                    <input type="submit" class="form-control mt-3 btn btn-primary" value="Save"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete-hours-modal-{{ $full_case_hours[$i]['id'] }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h3>
                                <i class="fas fa-clock"></i> Delete hours
                            </h3>
                            <div class="clearfix"></div>
                            <hr/>
                            <div class="col-xs-12">
                                <form method="POST" action="/dashboard/cases/case/hours/delete">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                    <input type="hidden" name="id" value="{{ $full_case_hours[$i]['id'] }}"/>
                                    <input type="hidden" name="type" value="{{ $full_case_hours[$i]['type'] }}"/>
                                    <p>
                                        {{ $full_case_hours[$i]['readable'] }}
                                        : {{ $full_case_hours[$i]['description'] }}
                                    </p>
                                    <p>
                                        If you'd like to delete these hours, click delete below!
                                    </p>
                                    <input type="submit" class="form-control mt-3 btn btn-danger" value="Delete hours"/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    @endif

    <div class="modal fade" id="payment-modal-full">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>
                        <i class="fas fa-tasks"></i> Create invoice
                    </h3>
                    <div class="clearfix"></div>
                    <hr/>
                    <form role="form" method="post" action="/dashboard/invoices/invoice/create">
                        <input type="hidden" name="case_uuid" value="{{ $case->case_uuid }}"/>
                        <input type="hidden" name="invoicable_id"
                               value="{{ !empty($invoicable_id) ? $invoicable_id : "" }}"/>
                        <input type="hidden" name="total_amount" value="{{ $invoice_amount }}"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-sm-12">
                            <label>Client</label>
                            @if(!empty($case->Contacts))
                                @foreach($case->Contacts as $contact)
                                    @if($contact->is_client)
                                        <input type="hidden" name="client_id" value="{{ $contact->id }}"/>
                                        <p>
                                            {{ $contact->first_name }} {{ $contact->last_name }}
                                        </p>
                                    @endif
                                @endforeach


                            @else
                                <input type="hidden" name="client_id"/>
                                <p>Enter a contact name to start creating one now!</p>
                                <input type="text" class="form-control" class="mb-3" name="contact_name"/>
                            @endif
                        </div>
                        <div class="col-sm-12">
                            <label>Description</label>
                            <input type="text" class="form-control" name="invoice_description"/>
                        </div>
                        <div class="col-sm-6 payment-double col-xs-12">
                            <label>Amount</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" value="{{ round($invoice_amount, 2) }}"
                                       name="amount"/>
                            </div>
                        </div>
                        <div class="col-sm-6 payment-double col-xs-12">
                            <label>Due date (configurable in firm)</label>
                            <input type="text" class="form-control dp"
                                   value="{{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->addDays(7)->format('m/d/Y') }}"
                                   id="invoice_date"
                                   name="invoice_date" aria-label="Invoice date">
                        </div>

                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-primary mt-2 form-control"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var events =  {!! json_encode($events->toArray()) !!};
        var u_id =
                {!! json_encode($user['id']) !!}
        var user =
        {!! json_encode($user) !!}
        /*if (show_task_calendar.length > 0) {
            //events.concat(show_task_calendar);
            events = $.merge(events, show_task_calendar);
        }*/

        //console.log(show_task_calendar);
        for (var i = 0; i < events.length; i++) {

            events[i].id = events[i]['id'];
            events[i].title = events[i]['name'];
            //events[i].client;
            events[i].start = events[i]['start_date'];
            events[i].end = events[i]['end_date'];
            console.log(events[i]);

            if (events[i].approved == '0') {
                events[i].color = 'gray';
            } else if (events[i].approved == '2') {
                events[i].color = 'red';
            }

            if (events[i].type == 'lunch') {
                events[i].color = 'green';
                events[i].textColor = 'white';
            } else if (events[i].type == 'blocker') {
                events[i].color = 'red';
                events[i].textColor = 'white';
            } else if (events[i].type == 'research') {
                events[i].color = 'aqua';
                events[i].textColor = 'black';
            } else if (events[i].type == 'booked') {
                events[i].color = 'light-blue';
                events[i].textColor = 'white';
            }

            if (events[i].hasOwnProperty('task_name')) {
                events[i].title = events[i]['task_name'];
                events[i].start = events[i]['due'];
                events[i].end = events[i]['due'];
                events[i].color = "purple";
            }
        }
        var $editable = true;

    </script>

    @hasanyrole('client')
    <script type="text/javascript">
        for (var i = 0; i < events.length; i++) {
            if (events[i]['co_id'] != u_id) {
                events[i].title = "Blocked";
                events[i].color = "orange";
            } else {
                events[i].title = events[i]['name'];
                //events[i].color = 'blue';
            }

        }
        var $editable = false;
    </script>
    @endhasrole




@endsection