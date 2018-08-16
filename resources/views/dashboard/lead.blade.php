@extends('adminlte::page')

@section('content')

  <div class="container dashboard contact col-sm-12 offset-sm-2">
	<nav class="nav nav-pills">
	  <a class="nav-item nav-link btn btn-info" href="/dashboard/leads"><i
				class="fas fa-arrow-left"></i> Back to Leads</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#leads-modal" href="#"><i class="fa fa-user-circle"></i>
		   Edit lead</a>
	  <a class="nav-item nav-link btn btn-info" href="#"><i class="fas fa-print"></i> Print {{ Request::segment(3) }}
	  </a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#add-notes-modal" href="#"><i
				class="fas fa-sticky-note"></i> Add note</a>
	  <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#add-communication-modal" href="#"><i
				class="fas fa-comments"></i> Log communication</a>
		<a class="nav-item nav-link btn btn-primary" data-toggle="modal" data-target="#convert-modal" href="#"><i
					class="fas fa-user"></i> Convert lead to client</a>
	  <a class="nav-item nav-link btn btn-danger" data-toggle="modal" data-target="#delete-modal" href="#"><i
				class="fas fa-trash-alt"></i> Delete {{ Request::segment(3) }}</a>



	</nav>

	@include('dashboard.includes.alerts')

	<div>

	  <div>
		<h1 class="pull-left ml-4 mt-4 mb-2">
		  <i class="fa fa-user-circle"></i> {{ ucfirst(Request::segment(3)) }}
		</h1>
		<div class="clearfix"></div>
		<p class="ml-3 mb-2">Clients shows all of your client information regarding all cases. Click on a client to show
		  information.</p>

	  </div>
	  <div>


		<div class="col-sm-6 col-12">
		  <label>Name</label>
		  <p>{{ $lead->first_name }} {{ $lead->last_name }}</p>
		  <label>Address</label>
		  <p><a href="#" class="mapThis"
				place="{{ $lead->address_1 }} {{ $lead->address_2 }} {{ $lead->city }} {{ $lead->state }} {{ $lead->zip }}"
				zoom="16">{{ $lead->address_1 }}
			  {{ $lead->address_2 }}<br/>
			  {{ $lead->city }}<br/>
			  {{ $lead->state }}<br/>
			  {{ $lead->zip }}</a></p>
		  <label>Phone</label>
		  <p><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></p>

		</div>
		<div class="col-sm-6 col-12">

		  <label>Company</label>
		  <p>{{ $lead->company }}</p>
		  <label>Company title</label>
		  <p>{{ $lead->company_title }}</p>

		  <label>Email</label>
		  <p><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></p>
		</div>
		<div class="clearfix"></div>

		@if(count($logs) > 0)
		  <div class="col-md-6 col-sm-12">
			<h3 class="mt-5 ml-3">
			  <i class="fas fa-user"></i>Communication Logs
			</h3>
			<table id="comm_logs"
				   class="table table-{{ $table_size }} table-responsive table-striped table-{{ $table_color }}">
			  <thead>
			  <tr class="bg-primary">
				<th>ID</th>
				  <th>Date logged</th>

				<th>Comm. Type</th>
				<th>Description</th>
			  </tr>
			  </thead>
			  <tbody>
			  @foreach($logs as $log)
				@if($log->type_id != 0)
				  <tr>
					<td>{{ $log->id }}</td>
					  <td>{{ $log->created_at }}</td>

					<td>{{ $log->comm_type }}</td>
					<td>{{ $log->log }}</td>
				  </tr>
				@endif
			  @endforeach
			  </tbody>
			</table>
		  </div>
		@endif


		@if(count($notes) > 0)
		  <div class="col-md-6 col-sm-12">
			<h3 class="mt-5 ml-3">
			  <i class="fas fa-sticky-note"></i> Notes
			</h3>
			<div class="clearfix"></div>
			<div class="mb-3 ml-3" style="overflow:hidden;">


			  @foreach($notes as $note)
				@if($note->lead_uuid != 0)
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
				@endif


			  @endforeach
			</div>
		  </div>
		@endif

		<div class="clearfix"></div>

		<div class="clearfix"></div>



	  </div>

	</div>


	<div class="modal fade" id="delete-modal">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-body">
			<h3>
			  <i class="fas fa-trash-alt"></i> Delete {{ Request::segment(3) }}
			</h3>
			<div class="clearfix"></div>
			<hr/>
			<form method="POST" action="/dashboard/leads/lead/delete">
			  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			  <input type="hidden" name="id" value="{{ $lead->id }}"/>
			  <p>Click the button below to confirm the {{ Request::segment(3) }} deletion.</p>
			  <button type="submit" class="form-control mt-3 btn btn-danger">
				Delete
			  </button>
			</form>
		  </div>
		</div>
	  </div>
	</div>



	  <div class="modal fade" id="convert-modal">
		  <div class="modal-dialog">
			  <div class="modal-content">
				  <div class="modal-body">
					  <h3>
						  <i class="fas fa-user-circle"></i> Convert lead to client
					  </h3>
					  <div class="clearfix"></div>
					  <hr/>
					  <form method="POST" action="/dashboard/leads/lead/convert">
						  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
						  <input type="hidden" name="lead_uuid" value="{{ $lead->lead_uuid }}"/>
						  <p>Click submit below to convert {{ $lead->first_name }} {{ $lead->last_name  }} into a client.</p>
						  <button type="submit" class="form-control mt-3 btn btn-primary">
							  Convert {{ $lead->first_name }} {{ $lead->last_name }} to a client
						  </button>
					  </form>
				  </div>
			  </div>
		  </div>
	  </div>



  <div class="modal fade" id="add-notes-modal">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-body">
		  <h3>
			<i class="fas fa-sticky-note"></i> Add Note
		  </h3>
		  <div class="clearfix"></div>
		  <hr/>
		  <form method="POST" action="/dashboard/leads/lead/notes/note/add">
			<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			<input type="hidden" name="lead_uuid" value="{{ $lead->lead_uuid }}"/>
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

  <div class="modal fade" id="add-communication-modal">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-body">
		  <h3>
			<i class="fas fa-sticky-note"></i> Add communication
		  </h3>
		  <div class="clearfix"></div>
		  <hr/>
		  <form method="POST" action="/dashboard/leads/lead/log-communication">
			<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			<input type="hidden" name="lead_id" value="{{ $lead->id }}"/>
			<label>Type</label>
			<select name="communication_type" class="form-control">
			  <option value="email">Email</option>
			  <option value="phone">Phone</option>
			  <option value="text">Text</option>
			  <option value="fax">Fax</option>
			</select>
			<label>Reason for communication</label>
			<textarea name="communication" class="form-control"></textarea>
			<button type="submit" class="form-control mt-3 btn btn-primary">
			  Submit
			</button>
		  </form>
		</div>
	  </div>
	</div>
  </div>


  <div class="modal fade" id="communication-modal">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-body">
		  <h3>
			<i class="fas fa-sticky-note"></i> Communication log
		  </h3>
		  <div class="clearfix"></div>
		  <hr/>
		  @foreach($logs as $log)
			<label>Type</label>


			{{ $log->comm_type }}

			<label>Log</label>

			{{ $log->log }}

		  @endforeach
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
			<form method="POST" action="/dashboard/leads/lead/note/edit">
			  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			  <input type="hidden" name="id" value="{{ $note->id }}"/>
			  <div class="form-group form-group-fw">
				<textarea class='form-control' name="note">{{ $note->note }}</textarea>
			  </div>
			  <input type="submit" class="form-control mt-3 btn btn-primary" value="Save"/>
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
			<form method="POST" action="/dashboard/leads/lead/note/delete">
			  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			  <input type="hidden" name="id" value="{{ $note->id }}"/>
			  <p>

			  </p>
			  <p>
				If you'd like to delete note, {{ $note->note }}, click delete below!
			  </p>
			  <input type="submit" class="form-control mt-3 btn btn-danger" value="Delete note"/>
			</form>
		  </div>
		</div>
	  </div>
	</div>
  @endforeach



@endsection