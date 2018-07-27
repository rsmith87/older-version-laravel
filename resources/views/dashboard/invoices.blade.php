@extends('adminlte::page')

@section('content')

<div class="container dashboard col-sm-10 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
		@hasanyrole('authenticated_user|administrator')
    <a class="nav-item nav-link btn btn-info" href="/dashboard/cases"><i class="fas fa-briefcase"></i> My cases</a>
		@endhasrole
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
   <div class="panel panel-primary">
      <div class="panel-heading" style="overflow:hidden;">
        <h1  class="pull-left mt-4 ml-3">
          <i class="fa fa-file-alt"></i> Invoices
        </h1>
				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Use Invoices to create invoices based off completed cases!  You can then create an account for the billable client to allow acccess to online payment.</p>
     	 
		 </div>
     <div class="panel-body">	 
			 @if(count($invoices) > 0)

       <table id="main" class="table table-responsive table-resposive table-striped table-hover mb-5 table-{{ $table_color }} table-{{ $table_size }}">
          <thead> 
            <tr>
              <th>Id</th>
              <th>Client</th>
			  <th>Invoice Amount</th>
			  <th>Paid</th>
            </tr> 
          </thead> 
          <tbody>


		  @foreach ($invoices as $invoice)
            
            <tr> 
              <td>{{ $invoice->invoice_uuid }}</td>
              <td>{{ $invoice->receiver_info }}</td>
			  <td>$ {{ $invoice->total }}.00</td>
			  <td>{{ $invoice->paid ? "Yes" : "No" }}</td>
            </tr> 
		  @endforeach
						<tr>
							<td></td>
							<td></td>
							<td>Total invoiced:  ${{ number_format($invoice->total, 2) }}</td>
						</tr>					
            <tr class="blank"></tr>
          </tbody> 
       </table>

			 @endif
		</div>
				
  </div>

 </div>
@endif

@endsection