<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Invoice: {{ $invoice->id }}</title>
</head>
<style>
  .col-sm-4{
	width:33%;
	float:left;
  }
  .clearfix{
	clear:both;
  }
  .pull-right{
	float:right;
  }
  table{
	margin-top:50px;
  }
  table td{
	padding-right:10px;
  }
</style>
<body>
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
		<b>Payment Due:</b> 2/22/2014<br>
	  </div>
	  <!-- /.col -->
	</div>
	<!-- /.row -->
	<div class="clearfix"></div>
	<!-- Table row -->
	<div class="row" style="margin-top:50px;">
	  <div class="col-xs-12 table-responsive">
		<table class="table table-striped">
		  <thead>
		  <tr>
			<th>Description</th>
			<th>Case</th>
			<th>Subtotal</th>
		  </tr>
		  </thead>
		  <tbody
		  <tr>
			<td>{{ $invoice->description }}</td>
			<td>{{ $invoice->sender_info }}</td>
			<td>${{ number_format($invoice->total, 2) }}</td>
		  </tr>
		  </tbody>
		</table>
	  </div>
	  <!-- /.col -->
	</div>
	<!-- /.row -->

	<div class="row">
	  <div class="col-xs-6" style="float:right">
		<p class="lead"><strong>Date due</strong>: 2/22/2014</p>

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

  </section>
  <!-- /.content -->
  <div class="clearfix"></div>

</body>
</html>

