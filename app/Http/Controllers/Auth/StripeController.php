<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Error\Card;
use Illuminate\Validation\Validator;
use Cartalyst\Stripe\Stripe;
use Jrean\UserVerification\Facades\UserVerification;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;
use App\User;

class StripeController extends Controller
{
  
  use VerifiesUsers;

  public function add_stripe_payment(Request $request)
  {

	  if(!$request->session()->get('u_i')){
		  $user = \Auth::id();
		  if(!$user){
			  return redirect()->back()->withErrors(['We did not have a user ID for payment']);
		  }
	  } else {
		  $user = $request->session()->get('u_i');
	  }

	  //getting expiration as one field - exp-date
	  //need to seperate the values in the field
	  //data sample: 02 / 20
	  //get only first two digits for month
	  //get only last two digits for year
	  //basic php


    
    $input = $request->all();
    
      $input = array_except($input,array('_token'));
      $stripe = Stripe::make(env('STRIPE_SECRET'));
      
      try {
        $token = $request->get('stripeToken');

        if (empty($token) || $token === "") {
          return redirect()->route('addmoney.paywithstripe');
        }
	      //need to have way to get user - needs to be through
	      $user = User::find($user);

        if(isset($input['cc_coupon_code']) && $input['cc_coupon_code'] != ""){
	        $charge = $user->newSubscription('main', env('STRIPE_SUB_PLAN'))->withCoupon($input['cc_coupon_code'])->create($token);
        } else {
	        $charge = $user->newSubscription('main', env('STRIPE_SUB_PLAN'))->create($token);
        }

	      if ($user->subscribed('main')) {
          /**
          * Write Here Your Database insert logic.
          */
          //if the user is already verified then we send them to login page
		      if($user->isVerified()){
		      	return redirect('/login')->with('status', 'Payment updated.  Please login to continue.');
		      }

		      //if the user is not verified then we send them the verification email to make sure they're good
	        event(new Registered($user));
	        UserVerification::generate($user);
	        UserVerification::send($user, 'Legalkeeper User Verification');

	        return redirect('/login')->with('status', 'Check your email for a link to verify your account!');

          
        } else {
          \Session::put('error','Money not added in wallet!!');
          return redirect()->route('addmoney.paywithstripe');
        }

      } catch (Exception $e) {
        
        \Session::put('error',$e->getMessage());
        return redirect()->route('addmoney.paywithstripe');
        
      } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
        
        \Session::put('error',$e->getMessage());
        return redirect()->route('addmoney.paywithstripe');
        
      } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {

        \Session::put('error',$e->getMessage());
        return redirect()->route('addmoney.paywithstripe');

      }

  }
  
  
  public function add_payment(Request $request)
  {
    return view('vendor/adminlte/payment');
  }

}
