@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-10 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
        <a class="nav-item nav-link btn btn-info" href="/dashboard/invoices"><i class="fas fa-file-invoice"></i> Open Invoices</a>
        <a class="nav-item nav-link btn btn-info" href="/dashboard/invoices/paid"><i class="fas fa-file-invoice"></i> Paid Invoices</a>

    </nav>
  
	@include('dashboard.includes.alerts')
  @if(count($invoices) === 0)
  <div class="alert alert-warning alert-dismissible fade in" role="alert">
  You haven't created an Invoice yet! <strong>Create one from the case page!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  </div>
  @else


        <h1  class="pull-left mt-4 ml-3">
            <i class="fas fa-file-invoice"></i> {{ Request::segment(3) === 'paid' ? "Paid" : "Open" }} Invoices
        </h1>
				<div class="clearfix"></div>

			 @if(count($invoices) > 0)


		  @foreach ($invoices as $invoice)
             <div class="invoice-small col-md-3 col-sm-6 col-xs-12 box-shadow box-white">
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
                 <a class="btn btn-primary btn-block" href="/dashboard/invoices/invoice/{{$invoice->invoice_uuid}}">View Invoice</a>
             </div>
		  @endforeach


			 @endif

 </div>
@endif

@endsection