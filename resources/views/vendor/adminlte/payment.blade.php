@extends('adminlte::master')



@section('body_class', 'login-page')

@section('body')

    <div class="login-box">
      @if (session('status'))
        <div class="alert alert-info fade in ml-3 mr-3 mb-4" role="alert">
          {{ session('status') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>			
        </div>
      @endif        
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            
         
      
                <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!!route('addmoney.stripe')!!}" >
               {{ csrf_field() }}
              <input  type='hidden' name="account_type">
               
              <input  type='hidden' name="amount" value="">

               <p>Complete registration by submitting your payment details below.  You will not be charged if you cancel before 14 days from registration.</p>
              
                <div class='col-xs-12 form-group card required'>
                <label class='control-label'>Card Number</label>
                <input autocomplete='off' class='form-control card-number' size='20' type='text' name="card_no">
                </div>
             
            
                <div class='col-xs-4 form-group cvc required'>
                <label class='control-label'>CVV</label>
                <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text' name="cvvNumber">
                </div>
               <div class="clearfix"></div>
                <div class='col-xs-4 form-group expiration required'>
                <label class='control-label'>Expiration</label>
                <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text' name="ccExpiryMonth">
                </div>
               
                <div class='col-xs-4 form-group expiration required'>
                <label class='control-label'> </label>
                <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text' name="ccExpiryYear">
                </div>
            

   
                <div class='col-md-12 form-group'>
                <button class='form-control btn btn-primary submit-button' type='submit'>Pay »</button>
                </div>
            
     
                <div class='col-md-12 error form-group hide'>
                <div class='alert-danger alert'>
                Please correct the errors and try again.
                </div>
                </div>
             
                </form>
            



        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

