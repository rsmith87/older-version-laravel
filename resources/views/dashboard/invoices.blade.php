@extends('adminlte::page')

@section('content')

<div class="container dashboard col-xs-12 offset-sm-2">
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
             <div class="invoice-small col-md-3 col-sm-5 col-xs-12 box-shadow box-white">
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
             <a class="btn btn-primary btn-block" href="/dashboard/invoices/invoice/{{$invoice->invoice_uuid}}">View</a>
             @if($invoice->paid != 1)
                 <a class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete-invoice-modal-{{$invoice->id}}">Delete</a>
             @endif
             </div>
		  @endforeach


			 @endif

 </div>
@endif

@if(count($invoices) > 0)


    @foreach ($invoices as $invoice)
<div class="modal fade" id="delete-invoice-modal-{{ $invoice->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3>
                    <i class="fas fa-sticky-note"></i> Send media
                </h3>
                <div class="clearfix"></div>
                <hr/>
                <p>Delete the below invoice by clicking the button below.</p>
                <p>{{ $invoice->description }}</p>
                <p>$ {{ $invoice->total }}.00</p>
                <a href="/dashboard/invoices/invoice/{{$invoice->invoice_uuid}}/delete" class="btn btn-danger btn-block">Delete invoice</a>
            </div>
        </div>
    </div>
</div>
    @endforeach
    @endif
@endsection