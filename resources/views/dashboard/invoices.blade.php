@extends('layouts.dashboard')

@section('content')

<div class="container dashboard col-sm-10 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-plus"></i> <i class="fas fa-briefcase"></i> Add case</a>
    <a class="nav-item nav-link btn btn-info"  data-toggle="modal" data-target="#user-modal" href="#"><i class="fas fa-briefcase"></i> My Cases</a>
  </nav>  
  

   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1  class="pull-left mt-4 ml-3">
          <i class="fa fa-file-alt"></i> Invoices
        </h1>
				<div class="clearfix"></div>
        <p class="ml-3 mb-2">Use Invoices to create invoices based off completed cases!  You can then create an account for the billable client to allow acccess to online payment.</p>
     	 	@include('dashboard.includes.alerts')
				@if(count($orders) === 0)
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
			  You haven't created an Invoice yet! <strong>Create one from the case page!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
				@endif
		 </div>
     <div class="panel-body">
			 @if(count($orders) > 0)
       <table class="table table-responsive table-resposive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
          <thead> 
            <tr>
              <th>Id</th>
              <th>Name</th>
							<th>Invoice Amount</th>
            </tr> 
          </thead> 
          <tbody> 
          @foreach ($orders as $order)
						@foreach($order->Invoices as $i)
            <tr> 
              <td>{{ $i->id }}</td>
              <td>{{ $i->receiver_info }}</td> 
							<td>$ {{ $i->total }}.00</td>
            </tr> 
						@endforeach
         	@endforeach
          </tbody> 
       </table>
			 @endif
		</div>
				
  </div>

 </div>


@endsection