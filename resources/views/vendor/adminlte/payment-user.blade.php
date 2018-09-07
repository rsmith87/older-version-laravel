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

        <input type="hidden" name="firm_id" value="{{ $firm_id }}" />
        <input type="hidden" name="sa_id" value="{{ $sa_id->id }}" />
        <input  type='hidden' name="account_type">

        <input  type='hidden' name="amount" value="20.00">

        <p>Complete registration by submitting your payment details below.  You will not be charged if you cancel before 14 days from registration.</p>
        <br /><br />
        <p><strong>Legalkeeper Starter Plan:</strong></p>
        <p>$20 a month (Tax included)</p>
        <p>Total: $20.00 per month per user</p>

        <label for="card-element">
          Credit or debit card
        </label>
        <div id="card-element">
          <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Used to display Element errors. -->
        <div id="card-errors" role="alert"></div>
        <br />
        <button class='form-control btn btn-primary submit-button' type='submit'>Pay »</button>


      </form>




    </div>
      <!-- /.login-box-body -->
  </div><!-- /.login-box -->

  <script src="https://js.stripe.com/v3/"></script>
  <script type="text/javascript">

      var stripe = Stripe('{{ env('STRIPE_KEY') }}');
      var elements = stripe.elements();

      // Create an instance of the card Element.
      var card = elements.create('card');

      // Add an instance of the card Element into the `card-element` <div>.
      card.mount('#card-element');

      card.addEventListener('change', function(event) {
          var displayError = document.getElementById('card-errors');
          if (event.error) {
              displayError.textContent = event.error.message;
          } else {
              displayError.textContent = '';
          }
      });


      // Create a token or display an error when the form is submitted.
      var form = document.getElementById('payment-form');
      form.addEventListener('submit', function(event) {
          event.preventDefault();

          stripe.createToken(card).then(function(result) {
              if (result.error) {
                  // Inform the customer that there was an error.
                  var errorElement = document.getElementById('card-errors');
                  errorElement.textContent = result.error.message;
              } else {
                  // Send the token to your server.
                  stripeTokenHandler(result.token);
              }
          });
      });

      function stripeTokenHandler(token) {
          // Insert the token ID into the form so it gets submitted to the server
          var form = document.getElementById('payment-form');
          var hiddenInput = document.createElement('input');
          hiddenInput.setAttribute('type', 'hidden');
          hiddenInput.setAttribute('name', 'stripeToken');
          hiddenInput.setAttribute('value', token.id);
          form.appendChild(hiddenInput);

          // Submit the form
          form.submit();
      }
  </script>
@stop
