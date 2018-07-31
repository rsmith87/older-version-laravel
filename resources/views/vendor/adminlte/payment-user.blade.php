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
            
         
      
            <form class="form-horizontal" method="POST" id="payment-form" role="form" action="/dashboard/firm/add-user-payment" >
               {{ csrf_field() }}

              <input  type='hidden' name="amount" value="15.00">
              <input type="hidden" name="firm_id" value="{{ $firm_id }}" />
				<br /><br />
				<p><strong>Legalkeeper Starter Plan:</strong></p>
				<p>$15 a month</p>
                <p>Tax: 8.25% : $1.24 </p>
                <p>Total: $16.24 per month per user</p>

                <p>We do not store your credit card information in any means, so unfortunately we require you to input it again.  Thanks for your understanding.</p>
              
                <div class='col-xs-12 form-group card required'>
                <label class='control-label'>Card Number</label>
                <input autocomplete='off' class='form-control card-number' size='20' type='text' name="card_no">
                </div>
             
            
                <div class='col-xs-4 form-group cvc required'>
                <label class='control-label'>CVV</label>
                <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text' name="cvvNumber">
                </div>
                <div class='col-xs-4 form-group expiration required'>
                <label class='control-label'>Expiration</label>
                <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text' name="ccExpiryMonth">
                </div>
               
                <div class='col-xs-4 form-group expiration required'>
                <label class='control-label'> &nbsp;</label>
                <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text' name="ccExpiryYear">
                </div>
<div class="clearfix"></div>
			  <div class='col-xs-12 form-group'>
				<label class='control-label'> Coupon code</label>
				<input class='form-control card-expiry-year'  type='text' name="cc_coupon_code">
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

