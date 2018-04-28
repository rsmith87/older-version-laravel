@extends('layouts.dashboard')

@section('content')

<div class="container settings dashboard col-sm-10 col-12 offset-sm-2">
	<nav class="nav nav-pills">
		<a class="nav-item nav-link btn btn-info" href="#general-settings"><i class="fa fa-cog"></i> General settings</a>		
    <a class="nav-item nav-link btn btn-info"  href="#theme-settings"><i class="fas fa-object-group"></i> Theme settings</a>    	
    <a class="nav-item nav-link btn btn-info" href="#table-data-selectors"><i class="fas fa-table"></i> Data table settings</a>
   <a class="nav-item nav-link btn btn-info" href="#stripe-settings"><i class="fab fa-cc-stripe"></i> Stripe settings</a>		
  </nav>  
	
   <div class="panel panel-default">
      <div class="panel-heading" style="overflow:hidden;">
        <h1 class="pull-left ml-3 mt-4 mb-2">
          <i class="fas fa-cog"></i> Settings
        </h1>
   			<div class="clearfix"></div>
        <p class="ml-3 mb-2">Change views, update Stripe info and more!.</p>							
						@include('dashboard.includes.alerts')			
     </div>	
     <div class="panel-body">

       <div class="clearfix"></div>
       <div class="col-12 col-sm-6">

         <form method="POST" action="/dashboard/settings/social-media">
           <input type="hidden" name="_token" value="{{ csrf_token() }}" />
           <label>Facebook URL</label>
           <input type="text" class="form-control" value="{{ count($fb) > 0 ? $fb : "" }}" name="fb" />
           <label>Twitter URL</label>
           <input type="text" class="form-control" value="{{ count($twitter) > 0 ? $twitter : "" }}" name="twitter" />
           <label>Instagram URL</label>
           <input type="text" class="form-control" value="{{ count($instagram) > 0 ? $instagram : "" }}" name="instagram" />
           <label>Avvo URL</label>
           <input type="text" class="form-control" value="{{ count($avvo) > 0 ? $avvo : "" }}" name="avvo" />
           <button type="submit" class="btn btn-primary">
             Submit
           </button>
         </form>
       </div>
       <div class="col-sm-6 col-12">
       <label for="show_tasks_calendar">Show tasks on calendar?</label>
       <form method="POST" action="/dashboard/settings/show-tasks-calendar">
		     <input type="hidden" name="_token" value="{{ csrf_token() }}">         
         <input type="checkbox" name="show" {{ $show_task_calendar ? "checked":'' }} />
       </form>         
       </div>

     </div>
  </div>   



   <div class="panel panel-default" id="theme-settings">
      <div class="panel-heading" style="overflow:hidden;">
        <h2 class="pull-left">
          <i class="fas fa-object-group"></i> Theme settings
        </h2>
     </div>
     <div class="panel-body">

       
  	 <div class="form-group">
       <label for="theme_selector">Theme</label>
		   <form method="post" id="theme-update" action="/dashboard/settings/theme-update">
		   <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <select class="form-control" name="theme_selector">
			 @foreach($themes as $t)
         	<option value="{{ $t->name }}" {{ $t->name == $theme ? "selected='selected'" : '' }}>{{ ucfirst($t->name) }}</option>
			 @endforeach
    	</select>
			</form>
  	 </div>
       
		 <div class="form-group">
			<label for="table_color">Table color</label>
			   <form method="post" id="table-color" action="/dashboard/settings/table-color-update">
		   <input type="hidden" name="_token" value="{{ csrf_token() }}">
	
			<select class="form-control" name="table_color">
				@foreach($table_color_options as $table_option)
         	<option value="{{ $table_option }}" {{ $table_option == $table_color ? "selected='selected'" : '' }}>{{ ucfirst($table_option) }}</option>
				@endforeach
			</select>
					 
					 
			 </form>
			 </div>
		 <div class="form-group">
			<label for="table_size">Table size</label>
			   <form method="post" id="table-size" action="/dashboard/settings/table-size">
		   <input type="hidden" name="_token" value="{{ csrf_token() }}">
	
			<select class="form-control" name="table_size">
				@foreach($table_sizes as $table_option)
         	<option value="{{ $table_option }}" {{ $table_option == $table_size ? "selected='selected'" : '' }}>{{ ucfirst($table_option) }}</option>
				@endforeach
			</select>
					 
					 
			 </form>			 
		 </div>

  
     </div>
  </div>   




   <div class="panel panel-default" id="table-data-selectors">
      <div class="panel-heading" style="overflow:hidden;">
        <h2 class="pull-left">
          <i class="fas fa-table"></i> Table data selectors
        </h2>
     </div>
     <div class="panel-body">

       
  <div class="form-group">

<ul class="nav nav-tabs">

  <li class="nav-item active">
    <a class="nav-link active" data-toggle="tab" href="#cases">Cases</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#clients">Clients</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#contacts">Contacts</a>
  </li>
</ul>		
		<div class="tab-content">
      <div id="cases" class="tab-pane active show fade">
		<h4>
					Fields
				</h4>
					<form method="post" action="/dashboard/views/case/update">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id" value="{{ $case_id }}">
					<input type="hidden" name="type" value="case">


					
						

				    @foreach ($case_columns as $column)					                
					 <div class="input-group mb-3">
             <div class="input-group-prepend">
               <div class="input-group-text">
<input type="checkbox" {{ !empty($case_user_columns) && in_array($column, $case_user_columns)  ? "checked value=1" : "" }} name="{{ $column }}" aria-label="{{ $column }}">
               </div>
             </div>
             <div class="input-group-append">
               <span class="input-group-text">{{ ucfirst(str_replace('_', ' ', $column)) }}</span>
             </div>
           </div>	
				    @endforeach

				

					<div class="clearfix"></div>
				<button type="submit" class="btn btn-primary">
					Submit
					</button>	
					</form>
			

      </div>
      <div id="clients" class="tab-pane fade">
        <h4>Fields</h4>
					<form method="post" action="/dashboard/views/client/update">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id" value="{{ $client_id }}">
					<input type="hidden" name="type" value="client">
					@foreach($client_columns as $column)
					 <div class="input-group mb-3">
             <div class="input-group-prepend">
               <div class="input-group-text">
                <input type="checkbox" {{ !empty($client_user_columns) && in_array($column, $client_user_columns) ? "checked value=1" : "" }} name="{{ $column }}" aria-label="{{ $column }}">
               </div>
             </div>
             <div class="input-group-append">
               <span class="input-group-text">{{ ucfirst(str_replace('_', ' ', $column)) }}</span>
             </div>
           </div>
					@endforeach		
						<div class="clearfix"></div>
				<button type="submit" class="btn btn-primary">
					Submit
					</button>							
				</form>
      </div>
      <div id="contacts" class="tab-pane show fade">
				<h4>
					Fields
				</h4>
					<form method="post" action="/dashboard/views/contact/update">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id" value="{{ $contact_id }}">
					<input type="hidden" name="type" value="contact">
					@foreach($contact_columns as $column)
					 <div class="input-group mb-3">
             <div class="input-group-prepend">
               <div class="input-group-text">
                <input type="checkbox" {{ !empty($contact_user_columns) && in_array($column, $contact_user_columns) ? "checked value=1" : "" }} name="{{ $column }}" aria-label="{{ $column }}">
               </div>
             </div>
             <div class="input-group-append">
               <span class="input-group-text">{{ ucfirst(str_replace('_', ' ', $column)) }}</span>
             </div>
           </div>
					@endforeach		
						<div class="clearfix"></div>
				<button type="submit" class="btn btn-primary">
					Submit
					</button>							
				</form>
      </div>

		</form>
    
    </div>

  </div>

  
     </div>

	</div>
 



   <div class="panel panel-default" id="stripe-settings">
      <div class="panel-heading">
        <h2 style="margin-top:0;margin-bottom:0;">
          <i class="fab fa-cc-stripe"></i> Stripe settings
        </h2>
     </div>
     <div class="panel-body">
			 @if(count($fs) > 0)
			 	<p>You have successfully authenticated Legalease and Stripe!  If you'd like to authenticate again, or are having issues with payments click the link below</p>
			 @endif
			 <a href="/dashboard/settings/stripe/create"><img src="{{ asset('img/blue-on-light.png') }}" /></a>
     </div>
  </div>   
	

</div>

<script type="text/javascript" src="{{ asset('js/pair-select.min.js') }}"></script>
@endsection