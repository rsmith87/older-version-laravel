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

class FirmStripeController extends Controller
{
  
  use VerifiesUsers;

  public function add_stripe_payment(Request $request)
  {
		  $user = \Auth::id();



    
    $input = $request->all();
    

      $stripe = Stripe::make(env('STRIPE_SECRET'));
      
      try {
          $token = $request->get('stripeToken');


          if (empty($token) || $token === "") {
              return redirect()->route('addmoney.paywithstripe')->withErrors(['Your card had trouble processing.  Please try again.']);
          }


        //SA_ID references the user account being created, now the account used to create the subaccount
	      //Currently it makes it easier to manage on because of the needed middleware for seeing if an account is subscribed
	      $subaccount = User::find($input['sa_id']);

        if(isset($input['cc_coupon_code']) && $input['cc_coupon_code'] != ""){
	        $charge = $subaccount->newSubscription('main', env('STRIPE_SUB_PLAN'))->withCoupon($input['cc_coupon_code'])->create($token);
        } else {
	        $charge = $subaccount->newSubscription('main', env('STRIPE_SUB_PLAN'))->create($token);
        }

	      if ($subaccount->subscribed('main')) {

		      //if the user is not verified then we send them the verification email to make sure they're good
		      event(new Registered($user));
	        return redirect('/dashboard/firm')->with('status', 'Firm member paid for and can be used for login!  An email to reset their password has been sent to the new users email.');

          
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
