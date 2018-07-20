@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-10 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
		@hasanyrole('authenticated_user|administrator')
    <a class="nav-item nav-link btn btn-info" href="/dashboard/cases"><i class="fas fa-briefcase"></i> My cases</a>
		@endhasrole
  </nav>  
  
	@include('dashboard.includes.alerts')
  @if(count($invoice) === 0)
  <div class="alert alert-warning alert-dismissible fade in" role="alert">
  You haven't created an Invoice yet! <strong>Create one from the case page!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  </div>
  @else
	  <!-- Content Header (Page header) -->
	  <section class="content-header">
		<h1>
		  Invoice
		  <small>#{{ $invoice->id }}</small>
		</h1>

	  </section>

	  <!-- Main content -->
	  <section class="invoice">
		<!-- title row -->
		<div class="row">
		  <div class="col-xs-12">
			<h2 class="page-header">
			  <i class="fa fa-globe"></i> {{ $firm->name }}
			  <small class="pull-right">Date: {{ \Carbon\Carbon::parse($invoice->created_at)->format('m/d/Y') }}</small>
			</h2>
		  </div>
		  <!-- /.col -->
		</div>
		<!-- info row -->
		<div class="row invoice-info">
		  <div class="col-sm-4 invoice-col">
			From
			<address>
			  <strong>{{ $firm->name }}</strong><br>
			  {{ $firm->address_1 }}<br>
			  {{ $firm->address_2 }}<br>
			  {{ $firm->city }}, {{ $firm->state }} {{ $firm->zip }}<br>
			</address>
		  </div>
		  <!-- /.col -->
		  <div class="col-sm-4 invoice-col">
			To
			<address>
			  <strong>{{ $client->first_name }} {{ $client->last_name }}</strong><br>
			  {{ $client->address_1 }}<br>
			  {{ $client->address_2 }}<br>
			  {{ $client->city }}, {{ $client->state }} {{ $client->zip }}<br>
			  Phone: {{ $client->phone }}<br>
			  Email: {{ $client->email }}
			</address>
		  </div>
		  <!-- /.col -->
		  <div class="col-sm-4 invoice-col">
			<b>Invoice #{{ $invoice->id }}</b><br>
			<br>
			<b>Payment Due:</b> 2/22/2014<br>
		  </div>
		  <!-- /.col -->
		</div>
		<!-- /.row -->

		<!-- Table row -->
		<div class="row">
		  <div class="col-xs-12 table-responsive">
			<table class="table table-striped">
			  <thead>
			  <tr>
				<th>Qty</th>
				<th>Case</th>
				<th>Serial #</th>
				<th>Description</th>
				<th>Subtotal</th>
			  </tr>
			  </thead>
			  <tbody
			  <tr>
				<td>{{ $invoice->id }}</td>
				<td>{{ $invoice->sender_info }}</td>
				<td>455-981-221</td>
				<td>{{ $invoice->description }}</td>
				<td>${{ number_format($invoice->total, 2) }}</td>
			  </tr>
			  </tbody>
			</table>
		  </div>
		  <!-- /.col -->
		</div>
		<!-- /.row -->

		<div class="row">
		  <!-- accepted payments column -->
		  <div class="col-xs-6">
			<p class="lead">Payment Methods:</p>
			<img src="{{ asset('img/visa.png') }}" alt="Visa">
			<img src="{{ asset('img/mastercard.png') }}" alt="Mastercard">
			<img src="{{ asset('img/american-express.png') }}" alt="American Express">
			<img src="{{ asset('img/paypal2.png') }}" alt="Paypal">

			<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
			  We expect prompt payment for services rendered.
			</p>
		  </div>
		  <!-- /.col -->
		  <div class="col-xs-6">
			<p class="lead">Amount Due 2/22/2014</p>

			<div class="table-responsive">
			  <table class="table">
				<tbody><tr>
				  <th style="width:50%">Subtotal:</th>
				  <td>${{ number_format($invoice->total, 2) }}</td>
				</tr>
				<tr>
				  <th>Tax (0%)</th>
				  <td>$0.00</td>
				</tr>
				<tr>
				  <th>Total:</th>
				  <td>${{ number_format($invoice->total, 2) }}</td>
				</tr>
				</tbody></table>
			</div>
		  </div>
		  <!-- /.col -->
		</div>
		<!-- /.row -->

		<!-- this row will not appear when printing -->
		<div class="row no-print">
		  <div class="col-xs-12">
			<a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
			<button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
			</button>
			<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
			  <i class="fa fa-download"></i> Generate PDF
			</button>
		  </div>
		</div>
	  </section>
	  <!-- /.content -->
	  <div class="clearfix"></div>

	@endif

@endsection