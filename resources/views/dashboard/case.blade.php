@extends('adminlte::page')

@section('content')

  <div class="container dashboard case col-sm-12 col-12 offset-sm-2">
	<nav class="nav nav-pills">
	  <a class="nav-item nav-link btn btn-info" href="/dashboard/cases"><i class="fas fa-arrow-left"></i> Back to cases</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-edit-modal" href="#"><i
				class="fas fa-balance-scale"></i> Edit case</a>
	   |
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target='#add-hours-modal' href='#'><i
				class="fas fa-clock"></i> Add hours worked</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#add-notes-modal" href="#"><i
				class="fas fa-sticky-note"></i> Add note</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i
				class="fas fa-calendar-plus"></i> Add event</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#contacts-modal" href="#">
		<i class="fas fa-user"></i> Add Contact
	  </a> |
	  <!--<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#document-modal" href="#"><i class="fas fa-cloud-upload-alt"></i> Add document</a>-->

	  @if(count($order) > 0)
		<a class="nav-item nav-link btn btn-info" href="/dashboard/invoices"><i class="fa fa-file"></i> View
		  invoices</a>
	  @endif

	  @if(count($case->Contacts) > 0)
		@foreach($case->Contacts as $contact)
		  @if($contact->is_client)
			<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#payment-modal-full" href="#"><i
					  class="fas fa-dollar-sign"></i> Bill client {{ $contact->first_name }} {{ $contact->last_name }}</a>
			<a class="nav-item nav-link btn btn-info" href="/dashboard/clients/client/{{ $contact->contlient_uuid }}"><i
					  class="fas fa-user"></i> View client {{ $contact->first_name }} {{ $contact->last_name }}</a>
			<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#change-client-modal" href="#">
			  <i class="fas fa-user"></i> Change client
			</a>
		  @endif
		@endforeach
	  @else
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#reference-modal-full" href="#">
		  <i class="fas fa-dollar-sign"></i> Reference client to case</a>
		<a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#client-modal" href="#">
		  <i class="fas fa-user"></i> Create client for case
		</a>
	  @endif
	  |	<a class="nav-item nav-link btn btn-info" href="/dashboard/cases/case/{{ $case->case_uuid }}/timeline"><i
				class="fas fa-heartbeat"></i> View timeline</a>
	<!-- <a class="nav-item nav-link btn btn-info" href="#"><i class="fas fa-user"></i> Case Progress</a> -->
	  <a class="nav-item nav-link btn btn-danger" data-toggle="modal" data-target="#delete-modal" href="#"><i
				class="fas fa-trash-alt"></i> Delete case</a>

	</nav>
	@include('dashboard.includes.alerts')

	<div>
	  <div>
		<h1 class="pull-left ml-3 mt-4 mb-2">
		  <i class="fas fa-balance-scale"></i> Case
		</h1>
		<div class="clearfix"></div>
		<p class="ml-3 mb-4">
		  Case information for {{ $case->name }}
		</p>
	  </div>
	  <div>
		<div class="col-sm-6 col-12">
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

		<div class="col-sm-6 col-12">
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

		<div class="clearfix"></div>

		@if(count($notes) > 0)
		  <div class="col-xs-12">
		  <h3 class="mt-5 ml-3">
			<i class="fas fa-sticky-note"></i> Case notes
		  </h3>
		  <div class="clearfix"></div>
		  <div class="mb-3 ml-3" style="overflow:hidden;">


			@foreach($notes as $note)
			  <div>

				<div class="card-body">
				  <a class="pull-right" data-toggle="modal" data-target="#delete-note-modal-{{ $note->id }}"><i
							class="fas fa-trash-alt"></i></a>
				  <a data-toggle="modal" class="pull-right" data-target="#edit-note-modal-{{ $note->id }}"><i
							class="fas fa-edit"></i></a>
				  <h5 class="card-title">
					Created: {{ \Carbon\Carbon::parse($note->created_at)->format('m/d/Y H:i:s') }}</h5>
				  <p class="card-text">{{ $note->note }}</p>
				</div>
			  </div>


			@endforeach
		  </div>
		  </div>
		@endif

		@if(count($case_hours) > 0)
		  <div class="col-xs-12">
			<h3 class="mt-5 ml-3">
			  <i class="fas fa-clock"></i> Case hours
			</h3>
			<div class="clearfix"></div>
			<div class="mb-3 ml-3" style="overflow:hidden;">


			  @foreach($case_hours as $case_hour)
				<div>
				@if($case_hour->hours != 0)
				  <div class="card-body">
					<a class="pull-right" data-toggle="modal" data-target="#delete-hours-modal-{{ $case_hour->id }}"><i
							  class="fas fa-trash-alt"></i></a>
					<a data-toggle="modal" class="pull-right" data-target="#edit-hours-modal-{{ $case_hour->id }}"><i
							  class="fas fa-edit"></i></a>
					<h5 class="card-title">
					  Created: {{ \Carbon\Carbon::parse($case_hour->created_at)->format('m/d/Y H:i:s') }}</h5>
					<p class="card-text"><strong>Hours</strong>: {{ $case_hour->hours }} <br /> <strong>Note</strong>: {{ $case_hour->note }}</p>
				  </div>
				</div>
				@endif


			  @endforeach
			</div>
		  </div>
		@endif
		<div class="clearfix"></div>


		@if(!empty($case->Contacts))
		  @foreach($case->Contacts as $contact)
			@if($contact->is_client === 1)
			  <div class="col-sm-6 col-xs-12">
				<h3 class="mt-5">
				  <i class="fas fa-user"></i> Client
				</h3>
				<table id="clients"
					   class="table table-{{ $table_size }} table-hover table-responsive table-striped table-{{ $table_color }} mb-3">
				  <thead>
				  <tr class="bg-primary">
					<th>Id</th>
					<th>Name</th>
					<th>Phone Number</th>
					<th>Email</th>

				  </tr>
				  </thead>
				  <tbody>
				  <tr>
					<td>{{ $contact->contlient_uuid }}</td>
					<td>{{ $contact->first_name }} {{ $contact->last_name }}</td>
					<td>{{ $contact->phone }}</td>
					<td>{{ $contact->email }}</td>
				  </tr>
				  </tbody>
				</table>
			  </div>
			@endif
		  @endforeach
		@endif

		@if(!empty($contacts))
		  @foreach($contacts as $contact)
			@if($contact->is_client != 1)
			  <div class="col-sm-6 col-xs-12">
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


		@if(count($case->Documents) > 0)
		  <div class="col-sm-6 col-xs-12">
			<h3 class="mt-5">
			  <i class="fa fa-file"></i> Documents
			</h3>
			<table id="documents"
				   class="table table-{{ $table_size }} table-hover table-responsive table-striped table-{{ $table_color }} mb-3">
			  <thead>
			  <tr class="bg-primary">
				<th>Id</th>
				<th>File name</th>
				<th>File description</th>

			  </tr>
			  </thead>
			  <tbody>
			  @foreach($case->Documents as $document)
				<tr>
				  <td>{{ $document->document_uuid }}</td>
				  <td>{{ $document->name }}</td>
				  <td>{{ $document->description }}</td>
				</tr>
			  @endforeach
			  </tbody>
			</table>
		  </div>
		@endif

		@if(!empty($media))
		  @if(count($media) > 0)
			<div class="col-sm-6 col-xs-12">
			  <h3 class="mt-5">
				<i class="fa fa-file"></i> Documents
			  </h3>
			  <table id="documents"
					 class="table table-{{ $table_size }} table-hover table-responsive table-striped table-{{ $table_color }} mb-3">
				<thead>
				<tr class="bg-primary">
				  <th>File name</th>
				  <th>File description</th>

				</tr>
				</thead>
				<tbody>
				@foreach($media as $m)
				  <tr>
					<td>{{ $m[0]->name }}</td>
					<td>{{ $m[0]->created_at }}</td>
				  </tr>
				@endforeach
				</tbody>
			  </table>
			</div>
		  @endif
		@endif


		@if(count($task_lists) > 0)
		  @foreach($task_lists as $task_list)
			<div class="col-sm-6 col-xs-12">
			  <h3 class="mt-5">
				<i class="fa fa-clipboard-list"></i> Task list {{ $task_list->task_list_name }}
			  </h3>
			  <table id="tasks"
					 class="table table-{{ $table_size }} table-hover table-responsive table-striped table-{{ $table_color }}">
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
		  <form method="post" action="/dashboard/documents/upload" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="col-sm-6 col-12">
			  <label for="file_upload">File</label>
			  <input type="file" class="form-control" name="file_upload"/>
			</div>
			<div class="col-sm-6 col-12">
			  <label for="file_name">File Name</label>
			  <input type="text" class="form-control" name="file_name" placeholder="File Name"/>
			</div>
			<div class="clearfix"></div>
			<div class="col-sm-6 col-12">
			  <label for="file_description">File Description</label>
			  <input type="text" class="form-control" name="file_description" placeholder="File Description"/>
			</div>
			<div class="clearfix"></div>
			<hr/>

			@hasanyrole('administrator')
			@if(count($cases) > 0)
			  <div class="col-sm-6 col-12">
				<label for="case_name">Case link</label>
				<input type="hidden" name="case_id" value="{{ $case->id }}"/>
				<input type="text" name="case_name" class="form-control" value="{{ $case->name }}"/>
			  </div>
			@endif
			@endhasanyrole
			@hasanyrole('client|authenticated_user')
			<input type="hidden" name="case_id" value="{{ $case->id }}"/>
			@endhasanyrole
			<div class="col-12">
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
			<label>Client</label>
			<input type="hidden" name="client_id"/>
			<select name="client" class="form-control">
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
			  <input type="text" class="form-control" id="name" value="{{ $case->name }}" name="name" aria-label="Name">
			</div>
			<div class="col-sm-12">


			  <label>Description</label>
			  <textarea class="form-control" aria-label="Description" name="description"
						id="description">{{ $case->description }}</textarea>
			</div>
			<div class="col-sm-6 col-xs-12">


			  <label>Case Number</label>
			  <input type="text" class="form-control" id="case_number" value="{{ $case->number }}" name="case_number"
					 aria-label="Case Number">
			</div>
			<div class="col-sm-6 col-xs-12">


			  <label>Court name</label>
			  <input type="text" class="form-control" id="court_name" value="{{ $case->court_name }}" name="court_name"
					 aria-label="Court name">
			</div>
			<div class="col-sm-6 col-xs-12">


			  <label>Opposing Councel</label>

			  <input type="text" class="form-control" id="opposing_councel" value="{{ $case->opposing_councel }}"
					 name="opposing_councel" aria-label="Opposing Councel">
			</div>
			<div class="col-sm-6 col-xs-12">


			  <label>Location</label>
			  <input type="text" class="form-control" id="location" value="{{ $case->location }}" name="location"
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
			  <input type="text" class="form-control datepicker"
					 value="{{ $case->open_date != "0000-00-00 00:00:00" ? \Carbon\Carbon::parse($case->open_date)->format('m/d/Y'): "" }}" id="open_date"
					 name="open_date" aria-label="Open date">
			</div>
			<div class="col-sm-6 col-xs-12">


			  <label>Close date</label>
			  <input type="text" class="form-control datepicker" id="close_date"
					 value="{{ $case->close_date != "0000-00-00 00:00:00" ? \Carbon\Carbon::parse($case->close_date)->format('m/d/Y') : ""}}"
					 name="close_date" aria-label="Close date">
			</div>

			<div class="col-sm-6 col-xs-12">


			  <label>Rate</label>
			  <input type="text" class="form-control" name="billing_rate" value="{{ $case->billing_rate }}"
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
				Warning: This is permanent!  If you'd like to delete this note, click delete below!
			  </p>
			  <input type="submit" class="form-control mt-3 btn btn-danger" value="Delete "/>
			</form>
		  </div>
		</div>
	  </div>
	</div>
  @endforeach

  @foreach($case_hours as $note)
	<div class="modal fade" id="edit-hours-modal-{{ $note->id }}">
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
			  <input type="hidden" name="id" value="{{ $note->id }}"/>
			  <div class="col-xs-12">
				<label>Hours</label>
			  <input type="text" class="form-control" name="hours" value="{{ $note->hours }}" />
			  </div>
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

	<div class="modal fade" id="delete-hours-modal-{{ $note->id }}">
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
			  <input type="hidden" name="id" value="{{ $note->id }}"/>
			  <p>
				{{ $note->hours }} : {{ $note->note }}
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
  @endforeach

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
			<input type="hidden" name="invoicable_id" value="{{ !empty($invoicable_id) ? $invoicable_id : "" }}"/>
			<input type="hidden" name="total_amount" value="{{ $invoice_amount }}.00"/>
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
			  <input type="text" class="form-control" value="{{ $invoice_amount }}.00" name="amount"/>
			  </div>
			</div>
			<div class="col-sm-6 payment-double col-xs-12">
			<label>Due date (configurable in firm)</label>
			<input type="text" class="form-control datepicker"
				   value="{{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->addDays(7)->format('m/d/Y') }}" id="invoice_date"
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


@endsection